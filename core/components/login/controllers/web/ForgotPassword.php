<?php
/**
 * Login
 *
 * Copyright 2010-2012 by Shaun McCormick <shaun+login@modx.com>
 *
 * Login is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
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
 * Handles the Forgot Password form for users
 *
 * @package login
 * @subpackage controllers
 */
class LoginForgotPasswordController extends LoginController {
    /** @var modUser $user */
    public $user;
    /** @var modUserProfile $profile */
    public $profile;
    /** @var string $templateToLoad */
    public $templateToLoad = 'lgnForgotPassTpl';
    /** @var string $templateTypeToLoad */
    public $templateTypeToLoad = 'modChunk';
    /** @var array $placeholders */
    public $placeholders = array();
    /** @var string $usernameField */
    public $usernameField = 'username';

    public function initialize() {
        $this->modx->lexicon->load('login:forgotpassword');
        $this->setDefaultProperties(array(
            'tpl' => 'lgnForgotPassTpl',
            'tplType' => 'modChunk',
            'sentTpl' => 'lgnForgotPassSentTpl',
            'sentTplType' => 'modChunk',
            'emailTpl' => 'lgnForgotPassEmail',
            'emailTplType' => 'modChunk',
            'emailSubject' => '',
            'preHooks' => '',
            'resetResourceId' => 1,
            'redirectTo' => false,
            'redirectParams' => '',
            'submitVar' => 'login_fp_service',
        ));
    }

    /**
     * Process the controller
     * @return string
     */
    public function process() {
        $this->templateToLoad = $this->getProperty('tpl');
        $this->templateTypeToLoad = $this->getProperty('tplType');

        /* get the request URI */
        $this->placeholders['loginfp.request_uri'] = empty($_POST['request_uri']) ? $this->login->getRequestURI() : $_POST['request_uri'];

        if ($this->hasPost()) {
            $this->handlePost();
            $fields = $this->dictionary->toArray();
            foreach ($fields as $k => $v) {
                $this->placeholders['loginfp.post.'.$k] = str_replace(array('[',']'),array('&#91;','&#93'),$v);
            }
        }

        return $this->login->getChunk($this->templateToLoad,$this->placeholders,$this->templateTypeToLoad);
    }

    /**
     * Handle the form submission
     * @return boolean
     */
    public function handlePost() {
        $this->loadDictionary();

        $success = false;
        $this->sanitizeFields();

        if ($this->runPreHooks()) {
            $this->fetchUser();

            if (empty($this->user)) {
                $this->placeholders['loginfp.errors'] = $this->modx->lexicon('login.user_err_nf_'.$this->usernameField);
            } else {
                $this->placeholders['email'] = $this->dictionary->get('email');

                $this->sendPasswordResetEmail();
                $this->templateToLoad = $this->getProperty('sentTpl');
                $this->templateTypeToLoad = $this->getProperty('sentTplType');
                $this->checkForRedirect();
            }
        }
        return $success;
    }

    /**
     * Fetch the user to update, also allowing external user updating
     * @return modUser
     */
    public function fetchUser() {
        $fields = $this->dictionary->toArray();
        $this->usernameField = 'username';
        $alias = 'modUser';
        if (empty($fields['username']) && !empty($fields['email'])) {
            $this->usernameField = 'email';
            $alias = 'Profile';
        }
        /* if the preHook didn't set the user info, find it by email/username */
        if (empty($fields[Login::FORGOT_PASSWORD_EXTERNAL_USER])) {
            /* get the user dependent on the retrieval method */
            $this->user = $this->login->getUserByField($this->usernameField,$fields[$this->usernameField],$alias);
            if ($this->user) {
                $fields = array_merge($fields,$this->user->toArray());
                $this->profile = $this->user->getOne('Profile');
                if ($this->profile) { /* merge in profile */
                    $fields = array_merge($this->profile->toArray(),$fields);
                }
            }
        }
        $this->dictionary->fromArray($fields);
        return $this->user;
    }

    /**
     * Check to see if the form has been submitted
     * @return boolean
     */
    public function hasPost() {
        $submitVar = $this->getProperty('submitVar','login_fp_service');
        return !empty($_POST) && !empty($_POST[$submitVar]);
    }

    /**
     * Sanitize the values sent on the form
     * @return void
     */
    public function sanitizeFields() {
        $fields = $this->dictionary->toArray();
        foreach ($fields as $k => $v) {
            $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93'),$v);
        }
        $this->dictionary->fromArray($fields);
    }

    /**
     * Run any preHooks to process before submitting the form
     * @return boolean
     */
    public function runPreHooks() {
        $success = true;
        $preHooks = $this->getProperty('preHooks','');
        if (!empty($preHooks)) {
            $this->loadHooks('preHooks');
            $this->preHooks->loadMultiple($preHooks,$this->dictionary->toArray(),array(
                'mode' => Login::MODE_FORGOT_PASSWORD,
            ));
            /* process preHooks */
            if ($this->preHooks->hasErrors()) {
                $success = false;
                $this->modx->toPlaceholders($this->preHooks->getErrors(),$this->getProperty('errorPrefix'));

                $errorMsg = $this->preHooks->getErrorMessage();
                $errorOutput = $this->modx->parseChunk($this->getProperty('errTpl'), array('msg' => $errorMsg));
                $this->modx->setPlaceholder('errors',$errorOutput);
            }

            $values = $this->preHooks->getValues();
            if (!empty($values)) {
                $this->dictionary->fromArray($values);
            }
        }
        return $success;
    }

    /**
     * Send an email to the user with a confirmation URL to reset their password at
     * @return void
     */
    public function sendPasswordResetEmail() {
        $fields = $this->dictionary->toArray();
        
        /* generate a password and encode it and the username into the url */
        $password = $this->login->generatePassword();
        $confirmParams = array(
            'lp' => urlencode(base64_encode($password)),
            'lu' => urlencode(base64_encode($fields['username']))
        );
        $confirmUrl = $this->modx->makeUrl($this->getProperty('resetResourceId',1),'',$confirmParams,'full');

        /* set the email properties */
        $emailProperties = $fields;
        $emailProperties['confirmUrl'] = $confirmUrl;
        $emailProperties['password'] = $password;
        $emailProperties['tpl'] = $this->getProperty('emailTpl');
        $emailProperties['tplType'] = $this->getProperty('emailTplType');

        /* now set new password to cache to prevent middleman attacks */
        $this->modx->cacheManager->set('login/resetpassword/'.$fields['username'],$password);

        $emailSubject = $this->getProperty('emailSubject','');
        $subject = !empty($emailSubject) ? $emailSubject : $this->modx->getOption('login.forgot_password_email_subject',null,$this->modx->lexicon('login.forgot_password_email_subject'));
        $this->login->sendEmail($fields['email'],$fields['username'],$subject,$emailProperties);
    }

    /**
     * Redirect the user to another page after successful form submission, if desired
     * @return boolean
     */
    public function checkForRedirect() {
        $redirectTo = $this->getProperty('redirectTo',false,'isset');
        /* if redirecting, do so here */
        if (!empty($redirectTo)) {
            $redirectParams = $this->getProperty('redirectParams','');
            if (!empty($redirectParams)) $redirectParams = $this->modx->fromJSON($redirectParams);
            $url = $this->modx->makeUrl($redirectTo,'',$redirectParams,'full');
            $this->modx->sendRedirect($url);
        }
        return !empty($redirectTo);
    }
}
return 'LoginForgotPasswordController';