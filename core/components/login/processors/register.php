<?php
/**
 * Login
 *
 * Copyright 2010 by Jason Coward <jason@modxcms.com> and Shaun McCormick
 * <shaun@modxcms.com>
 *
 * Login is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Login is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Login; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * Handle register form
 *
 * @package login
 * @subpackage processors
 */
class LoginRegisterProcessor extends LoginProcessor {
    /** @var modUser $user */
    public $user;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var array $userGroups */
    public $userGroups = array();

    public $persistParams = array();
    public $live = true;

    /**
     * @return mixed
     */
    public function process() {
        $this->cleanseFields();

        /* create user and profile */
        $this->user = $this->modx->newObject('modUser');
        $this->profile = $this->modx->newObject('modUserProfile');

        if ($this->controller->getProperty('useExtended',true,'isset')) {
            $this->setExtended();
        }

        $this->setUserFields();

        $this->setUserGroups();

        /* save user */
        if ($this->live) {
            if (!$this->user->save()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] Could not save newly registered user: '.$this->user->get('id').' with username: '.$this->user->get('username'));
                return $this->modx->lexicon('register.user_err_save');
            }
        }


        $this->preparePersistentParameters();

        /* send activation email (if chosen) */
        $email = $this->profile->get('email');
        $activation = $this->controller->getProperty('activation',true);
        $activateResourceId = $this->controller->getProperty('activationResourceId','');
        $moderated = $this->checkForModeration();
        if ($activation && !empty($email) && !empty($activateResourceId) && !$moderated) {
            $this->sendActivationEmail();

        } else if (!$moderated) {
            $this->user->set('active',true);
            if ($this->live) {
                $this->user->save();
            }
        }

        $this->runPostHooks();

        $this->checkForModerationRedirect();

        $this->checkForRegisteredRedirect();
        
        $successMsg = $this->controller->getProperty('successMsg','');
        $this->modx->toPlaceholder('error.message',$successMsg);
        return true;
    }

    /**
     * Remove any fields used for anti-spam, submission or moderation from the submission returns
     * @return void
     */
    public function cleanseFields() {
        $this->dictionary->remove('nospam');
        $this->dictionary->remove('blank');
        $submitVar = $this->controller->getProperty('submitVar');
        if (!empty($submitVar)) {
            $this->dictionary->remove($submitVar);
        }
    }

    /**
     * If wanted, set extra values in the form to profile extended field
     * @return void
     */
    public function setExtended() {
        /* first cut out regular and unwanted fields */
        $excludeExtended = $this->controller->getProperty('excludeExtended','');
        $excludeExtended = explode(',',$excludeExtended);
        $profileFields = $this->profile->toArray();
        $userFields = $this->user->toArray();
        $extended = array();
        $fields = $this->dictionary->toArray();
        foreach ($fields as $field => $value) {
            if (!isset($profileFields[$field]) && !isset($userFields[$field]) && $field != 'password_confirm' && $field != 'passwordconfirm' && !in_array($field,$excludeExtended)) {
                $extended[$field] = $value;
            }
        }
        /* now set extended data */
        $this->profile->set('extended',$extended);
    }

    /**
     * Setup the user data and create the user/profile objects
     * 
     * @return void
     */
    public function setUserFields() {
        $fields = $this->dictionary->toArray();
        /* allow overriding of class key */
        if (empty($fields['class_key'])) $fields['class_key'] = 'modUser';

        /* set user and profile */
        $this->user->fromArray($fields);
        $this->user->set('username',$fields[$this->controller->getProperty('usernameField','username')]);
        $this->user->set('active',0);
        $version = $this->modx->getVersionData();
        /* 2.1.x+ */
        if (version_compare($version['full_version'],'2.1.0-rc1') >= 0) {
            $this->user->set('password',$fields['password']);
        } else { /* 2.0.x */
            $this->user->set('password',md5($fields['password']));
        }
        $this->profile->fromArray($fields);
        $this->user->addOne($this->profile,'Profile');
    }

    /**
     * If user groups were passed, set them here
     * @return array
     */
    public function setUserGroups() {
        $added = array();
        /* if usergroups set */
        $this->userGroups = $this->controller->getProperty('usergroups','');
        if (!empty($this->userGroups)) {
            $this->userGroups = explode(',',$this->userGroups);

            foreach ($this->userGroups as $userGroupMeta) {
                $userGroupMeta = explode(':',$userGroupMeta);
                if (empty($userGroupMeta[0])) continue;

                /* get usergroup */
                $pk = array();
                $pk[intval($userGroupMeta[0]) > 0 ? 'id' : 'name'] = trim($userGroupMeta[0]);
                /** @var modUserGroup $userGroup */
                $userGroup = $this->modx->getObject('modUserGroup',$pk);
                if (!$userGroup) continue;

                /* get role */
                $rolePk = !empty($userGroupMeta[1]) ? $userGroupMeta[1] : 'Member';
                /** @var modUserGroupRole $role */
                $role = $this->modx->getObject('modUserGroupRole',array('name' => $rolePk));

                /* create membership */
                /** @var modUserGroupMember $member */
                $member = $this->modx->newObject('modUserGroupMember');
                $member->set('member',0);
                $member->set('user_group',$userGroup->get('id'));
                if (!empty($role)) {
                    $member->set('role',$role->get('id'));
                } else {
                    $member->set('role',1);
                }
                $this->user->addMany($member,'UserGroupMembers');
                $added[] = $userGroup->get('name');
            }
        }
        return $added;
    }

    /**
     * Setup persistent parameters to go through the request cycle
     * @return array
     */
    public function preparePersistentParameters() {
        $this->persistParams = $this->controller->getProperty('persistParams','');
        if (!empty($this->persistParams)) $this->persistParams = $this->modx->fromJSON($this->persistParams);
        if (empty($this->persistParams) || !is_array($this->persistParams)) $this->persistParams = array();
        return $this->persistParams;
    }

    /**
     * Send an activation email to the user with an encrypted username and password hash, to allow for secure
     * activation processes that are not vulnerable to middle-man attacks.
     * 
     * @return boolean
     */
    public function sendActivationEmail() {
        $emailProperties = $this->gatherActivationEmailProperties();

        /* send either to user's email or a specified activation email */
        $activationEmail = $this->controller->getProperty('activationEmail',$this->user->get('email'));
        $subject = $this->controller->getProperty('activationEmailSubject',$this->modx->lexicon('register.activation_email_subject'));
        return $this->login->sendEmail($activationEmail,$this->user->get('username'),$subject,$emailProperties);
    }

    /**
     * Get all the properties for the activation email
     * @return array
     */
    public function gatherActivationEmailProperties() {
        /* generate a password and encode it and the username into the url */
        $pword = $this->login->generatePassword();
        $confirmParams['lp'] = urlencode(base64_encode($pword));
        $confirmParams['lu'] = urlencode(base64_encode($this->user->get('username')));
        $confirmParams = array_merge($this->persistParams,$confirmParams);

        /* if using redirectBack param, set here to allow dynamic redirection
         * handling from other forms.
         */
        $redirectBack = $this->modx->getOption('redirectBack',$_REQUEST,$this->controller->getProperty('redirectBack',''));
        if (!empty($redirectBack)) {
            $confirmParams['redirectBack'] = $redirectBack;
        }
        $redirectBackParams = $this->modx->getOption('redirectBackParams',$_REQUEST,$this->controller->getProperty('redirectBackParams',''));
        if (!empty($redirectBackParams)) {
            $confirmParams['redirectBackParams'] = $redirectBackParams;
        }

        /* generate confirmation url */
        if ($this->login->inTestMode) {
            $confirmUrl = $this->modx->makeUrl(1,'',$confirmParams,'full');
        } else {
            $confirmUrl = $this->modx->makeUrl($this->controller->getProperty('activationResourceId',1),'',$confirmParams,'full');
        }

        /* set confirmation email properties */
        $emailTpl = $this->controller->getProperty('activationEmailTpl','lgnActivateEmailTpl');
        $emailTplAlt = $this->controller->getProperty('activationEmailTplAlt','');
        $emailTplType = $this->controller->getProperty('activationEmailTplType','modChunk');
        $emailProperties = $this->user->toArray();
        $emailProperties['confirmUrl'] = $confirmUrl;
        $emailProperties['tpl'] = $emailTpl;
        $emailProperties['tplAlt'] = $emailTplAlt;
        $emailProperties['tplType'] = $emailTplType;
        $emailProperties['password'] = $this->dictionary->get('password');

        $this->setCachePassword($pword);
        return $emailProperties;
    }

    public function setCachePassword($password) {
        /* now set new password to registry to prevent middleman attacks.
         * Will read from the registry on the confirmation page. */
        $this->modx->getService('registry', 'registry.modRegistry');
        $this->modx->registry->addRegister('login','registry.modFileRegister');
        $this->modx->registry->login->connect();
        $this->modx->registry->login->subscribe('/useractivation/');
        $this->modx->registry->login->send('/useractivation/',array($this->user->get('username') => $password),array(
            'ttl' => ($this->controller->getProperty('activationttl',180)*60),
        ));
        /* set cachepwd here to prevent re-registration of inactive users */
        $this->user->set('cachepwd',md5($password));
        if ($this->live) {
            $success = $this->user->save();
        } else {
            $success = true;
        }
        if (!$success) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] Could not update cachepwd for activation for User: '.$this->user->get('username'));
        }
        return $success;
    }

    /**
     * Check to see if a pre/post hook told Login to set the user to moderated (inactive) status
     * @return boolean
     */
    public function checkForModeration() {
        $moderate = $this->dictionary->get('register.moderate');
        return !empty($moderate);
    }

    /**
     * Run any post-registration hooks
     * 
     * @return void
     */
    public function runPostHooks() {
        $postHooks = $this->controller->getProperty('postHooks','');
        $this->controller->loadHooks('postHooks');
        $fields['register.user'] =& $this->user;
        $fields['register.profile'] =& $this->profile;
        $fields['register.usergroups'] = $this->userGroups;
        $this->controller->postHooks->loadMultiple($postHooks,$fields);

        /* process hooks */
        if ($this->controller->postHooks->hasErrors()) {
            $errors = array();
            $hookErrors = $this->controller->postHooks->getErrors();
            foreach ($hookErrors as $key => $error) {
                $errors[$key] = str_replace('[[+error]]',$error,$this->controller->getProperty('errTpl'));
            }
            $this->modx->toPlaceholders($errors,'error');

            $errorMsg = $this->controller->postHooks->getErrorMessage();
            $this->modx->toPlaceholder('message',$errorMsg,'error');
        }
    }

    /**
     * if a hook set the user as moderated, if set, send to an optional other moderation resource id
     * @return boolean
     */
    public function checkForModerationRedirect() {
        $moderated = $this->checkForModeration();
        if (!empty($moderated)) {
            $moderatedResourceId = $this->controller->getProperty('moderatedResourceId','');
            if (!empty($moderatedResourceId)) {
                $persistParams = array_merge($this->persistParams,array(
                    'username' => $this->user->get('username'),
                    'email' => $this->profile->get('email'),
                ));
                $url = $this->modx->makeUrl($moderatedResourceId,'',$persistParams,'full');
                if (!$this->login->inTestMode) {
                    $this->modx->sendRedirect($url);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Check for a redirect if the user was successfully registered. If one found, redirect.
     * 
     * @return boolean
     */
    public function checkForRegisteredRedirect() {
        /* if provided a redirect id, will redirect to that resource, with the
         * GET params `username` and `email` for you to use */
        $submittedResourceId = $this->controller->getProperty('submittedResourceId','');
        if (!empty($submittedResourceId)) {
            $persistParams = array_merge($this->persistParams,array(
                'username' => $this->user->get('username'),
                'email' => $this->profile->get('email'),
            ));
            $url = $this->modx->makeUrl($submittedResourceId,'',$persistParams,'full');
            if (!$this->login->inTestMode) {
                $this->modx->sendRedirect($url);
            }
            return true;
        }
        return false;
    }
}
return 'LoginRegisterProcessor';