<?php
/**
 * Login
 *
 * Copyright 2010 by Shaun McCormick <shaun+login@modx.com>
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
 * Handles updating the profile of the active user
 *
 * @package login
 * @subpackage controllers
 */
class LoginUpdateProfileController extends LoginController {
    /** @var boolean $hasPosted */
    public $hasPosted = false;

    /** @var modUserProfile $profile */
    public $profile;

    /**
     * Load default properties for this controller
     * @return void
     */
    public function initialize() {
        $this->modx->lexicon->load('login:updateprofile');
        $this->modx->lexicon->load('login:register');
        $this->setDefaultProperties(array(
            'allowedExtendedFields' => '',
            'emailField' => 'email',
            'errTpl' => '<span class="error">[[+error]]</span>',
            'excludeExtended' => '',
            'placeholderPrefix' => '',
            'postHooks' => '',
            'preHooks' => '',
            'redirectToLogin' => true,
            'reloadOnSuccess' => true,
            'submitVar' => 'login-updprof-btn',
            'successKey' => 'updpsuccess',
            'successMsg' => $this->modx->lexicon('login.profile_updated'),
            'successMsgPlaceholder' => 'error.message',
            'syncUsername' => false,
            'useExtended' => true,
            'validate' => '',
        ));
    }

    /**
     * Handle the UpdateProfile snippet business logic
     * @return string
     */
    public function process() {
        if (!$this->verifyAuthentication()) return '';
        if (!$this->getProfile()) return '';
        
        $this->setFieldPlaceholders();
        $this->checkForSuccessMessage();
        if ($this->hasPost()) {
            $this->loadDictionary();
            if ($this->validate()) {
                if ($this->runPreHooks()) {
                    /* update the profile */
                    $result = $this->runProcessor('UpdateProfile');
                    if ($result !== true) {
                        $this->modx->toPlaceholder('message',$result,'error');
                    } else if ($this->getProperty('reloadOnSuccess',true,'isset')) {
                        $url = $this->modx->makeUrl($this->modx->resource->get('id'),'',array(
                            $this->getProperty('successKey','updpsuccess') => 1,
                        ),'full');
                        $this->modx->sendRedirect($url);
                    } else {
                        $this->modx->setPlaceholder('login.update_success',true);
                    }
                }
            }
        }
        return '';
    }

    /**
     * Verify the user is logged in; otherwise redirect or return false
     * @return boolean
     */
    public function verifyAuthentication() {
        $authenticated = true;
        if (!$this->modx->user->hasSessionContext($this->modx->context->get('key'))) {
            $authenticated = false;
            if ($this->getProperty('redirectToLogin',true,'isset')) {
                $this->modx->sendUnauthorizedPage();
            }
        }
        return $authenticated;
    }

    /**
     * Get the Profile of the active user
     * @return modUserProfile
     */
    public function getProfile() {
        $this->profile = $this->modx->user->getOne('Profile');
        if (empty($this->profile)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find profile for user: '.$this->modx->user->get('username'));
        }
        return $this->profile;
    }

    /**
     * Set the user data as placeholders
     * @return void
     */
    public function setFieldPlaceholders() {
        $placeholders = $this->profile->toArray();
        /* add extended fields to placeholders */
        if ($this->getProperty('useExtended',true,'isset')) {
            $extended = $this->profile->get('extended');
            if (!empty($extended) && is_array($extended)) {
                $placeholders = array_merge($extended,$placeholders);
            }
        }
        $this->modx->toPlaceholders($placeholders,$this->getProperty('placeholderPrefix'));
    }

    /**
     * Look for a success message by the previous updating
     * @return void
     */
    public function checkForSuccessMessage() {
        if (!empty($_REQUEST[$this->getProperty('successKey','updpsuccess')])) {
            $this->modx->setPlaceholder('login.update_success',true);
        }
    }

    /**
     * See if the form has been submitted
     * @return boolean
     */
    public function hasPost() {
        $submitVar = $this->getProperty('submitVar');
        return (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar])));
    }

    /**
     * Validate the form submission
     * 
     * @return boolean
     */
    public function validate() {
        $validated = false;
        $this->loadValidator();
        $fields = $this->validator->validateFields($this->dictionary,$this->getProperty('validate',''));
        foreach ($fields as $k => $v) {
            $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
        }
        $this->dictionary->fromArray($fields);

        $this->removeSubmitVar();
        $this->preventDuplicateEmails();

        if ($this->validator->hasErrors()) {
            $placeholderPrefix = $this->getProperty('placeholderPrefix');
            $this->modx->toPlaceholders($this->validator->getErrors(),$placeholderPrefix.'error');
            $this->modx->toPlaceholders($this->dictionary->toArray(),$placeholderPrefix);
        } else {
            $validated = true;
        }
        return $validated;
    }

    /**
     * Remove the submitVar from the field list
     * @return void
     */
    public function removeSubmitVar() {
        $submitVar = $this->getProperty('submitVar');
        if (!empty($submitVar)) {
            $this->dictionary->remove($submitVar);
        }
    }

    /**
     * If allow_multiple_emails setting is false, prevent duplicate emails
     * @return void
     */
    public function preventDuplicateEmails() {
        $emailField = $this->getProperty('emailField','email');
        $email = $this->dictionary->get($emailField);
        if (!empty($email) && !$this->modx->getOption('allow_multiple_emails',null,false)) {
            $emailTaken = $this->modx->getObject('modUserProfile',array(
                'email' => $email,
                'id:!=' => $this->modx->user->get('id'),
            ));
            if ($emailTaken) {
                $this->validator->addError($emailField,$this->modx->lexicon('login.email_taken',array('email' => $email)));
            }
        }
    }

    /**
     * Run any preHooks for this snippet, that allow it to stop the form as submitted
     * @return boolean
     */
    public function runPreHooks() {
        $validated = true;
        $preHooks = $this->getProperty('preHooks','');
        if (!empty($preHooks)) {
            $this->loadHooks('preHooks');
            $this->preHooks->loadMultiple($preHooks,$this->dictionary->toArray(),array(
                'submitVar' => $this->getProperty('submitVar'),
                'redirectToLogin' => $this->getProperty('redirectToLogin',true,'isset'),
                'reloadOnSuccess' => $this->getProperty('reloadOnSuccess',true,'isset'),
            ));
            $values = $this->preHooks->getValues();
            if (!empty($values)) {
                $this->dictionary->fromArray($values);
            }

            if ($this->preHooks->hasErrors()) {
                $errors = array();
                $es = $this->preHooks->getErrors();
                $errTpl = $this->getProperty('errTpl');
                foreach ($es as $key => $error) {
                    $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
                }
                $this->modx->toPlaceholders($errors,'error');

                $errorMsg = $this->preHooks->getErrorMessage();
                $this->modx->toPlaceholder('message',$errorMsg,'error');
                $validated = false;
            }
        }
        return $validated;
    }
}
return 'LoginUpdateProfileController';