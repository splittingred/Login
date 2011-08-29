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
 * Handles registration of users
 *
 * @package login
 * @subpackage controllers
 */
class LoginRegisterController extends LoginController {
    /** @var boolean $hasPosted */
    public $hasPosted = false;

    /**
     * Load default properties for this controller
     * @return void
     */
    public function initialize() {
        $this->modx->lexicon->load('login:register');
        $this->setDefaultProperties(array(
            'activation' => true,
            'activationEmail' => '',
            'activationEmailSubject' => $this->modx->lexicon('register.activation_email_subject'),
            'activationEmailTpl' => 'lgnActivateEmailTpl',
            'activationEmailTplType' => 'modChunk',
            'activationResourceId' => '',
            'emailField' => 'email',
            'errTpl' => '<span class="error">[[+error]]</span>',
            'excludeExtended' => '',
            'moderatedResourceId' => '',
            'passwordField' => 'password',
            'persistParams' => '',
            'placeholderPrefix' => '',
            'preHooks' => '',
            'postHooks' => '',
            'redirectBack' => '',
            'redirectBackParams' => '',
            'submittedResourceId' => '',
            'submitVar' => 'login-register-btn',
            'successMsg' => '',
            'useExtended' => true,
            'usergroups' => '',
            'usernameField' => 'username',
            'validate' => '',
        ));
    }

    /**
     * Handle the Register snippet business logic
     * @return string
     */
    public function process() {
        $this->checkForPost();
        $this->preLoad();

        if (!$this->hasPosted) {
            return '';
        }

        if (!$this->loadDictionary()) {
            return '';
        }
        $this->validateFields();

        $this->validateUsername();
        $this->validatePassword();
        $this->validateEmail();

        $placeholderPrefix = $this->getProperty('placeholderPrefix','');
        if ($this->validator->hasErrors()) {
            $this->modx->toPlaceholders($this->validator->getErrors(),$placeholderPrefix.'error');
            $this->modx->setPlaceholder($placeholderPrefix.'validation_error',true);
        } else {

            $this->loadPreHooks();

            /* process hooks */
            if ($this->preHooks->hasErrors()) {
                $this->modx->toPlaceholders($this->preHooks->getErrors(),$placeholderPrefix.'error');
                $errorMsg = $this->preHooks->getErrorMessage();
                $this->modx->setPlaceholder($placeholderPrefix.'error.message',$errorMsg);
            } else {
                /* everything good, go ahead and register */
                $result = $this->runProcessor('Register');
                if ($result !== true) {
                    $this->modx->setPlaceholder($placeholderPrefix.'error.message',$result);
                }
            }
        }

        $this->modx->setPlaceholders($this->dictionary->toArray(),$placeholderPrefix);
        return '';
    }

    /**
     * Load any pre-registration hooks
     * @return void
     */
    public function loadPreHooks() {
        $preHooks = $this->getProperty('preHooks','');
        $this->loadHooks('preHooks');
        
        if (!empty($preHooks)) {
            $fields = $this->dictionary->toArray();
            /* do pre-register hooks */
            $this->preHooks->loadMultiple($preHooks,$fields,array(
                'submitVar' => $this->getProperty('submitVar'),
                'usernameField' => $this->getProperty('usernameField','username'),
            ));
            if (!empty($this->preHooks->fields)) {
                $this->dictionary->fromArray($this->preHooks->fields);
            }
        }
    }

    /**
     * Validate the fields in the form
     * @return array
     */
    public function validateFields() {
        $this->loadValidator();
        $fields = $this->validator->validateFields($this->dictionary,$this->getProperty('validate',''));
        foreach ($fields as $k => $v) {
            $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
        }

        return $fields;
    }

    /**
     * Ensure the username field is being sent and the username is not taken
     * 
     * @return boolean
     */
    public function validateUsername() {
        $usernameField = $this->getProperty('usernameField','username');
        $username = $this->dictionary->get($usernameField);
        $success = true;
        
        /* ensure username field exists and isn't empty */
        if (empty($username)) {
            $this->validator->addError($usernameField,$this->modx->lexicon('register.field_required'));
            $success = false;
        } else {
            /* make sure username isnt taken */
            /** @var modUser $alreadyExists */
            $alreadyExists = $this->modx->getObject('modUser',array('username' => $username));
            if ($alreadyExists) {
                $cachePwd = $alreadyExists->get('cachepwd');
                if ($alreadyExists->get('active') == 0 && !empty($cachePwd)) {
                    /* if inactive and has a cachepwd, probably an expired
                     * activation account, so let's remove it
                     * and let user re-register
                     */
                    if (!$alreadyExists->remove()) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] Could not remove old, deactive user with cachepwd.');
                        $success = false;
                    }
                } else {
                    $this->validator->addError($usernameField,$this->modx->lexicon('register.username_taken'));
                    $success = false;
                }
            }
        }
        return $success;
    }

    /**
     * Validate the password field
     *
     * @return boolean
     */
    public function validatePassword() {
        $passwordField = $this->getProperty('passwordField','password');
        $password = $this->dictionary->get($passwordField);
        $success = true;

        /* ensure password field isn't empty */
        if (empty($password)) {
            $this->validator->addError($passwordField,$this->modx->lexicon('register.field_required'));
            $success = false;
        }
        return $success;
    }

    /**
     * Validate the email address, and ensure it is not empty or already taken
     * @return boolean
     */
    public function validateEmail() {
        $emailField = $this->getProperty('emailField','email');
        $email = $this->dictionary->get('email');
        $success = true;

        /* ensure email field isn't empty */
        if (empty($email)) {
            $this->validator->addError($emailField,$this->modx->lexicon('register.field_required'));
            $success = false;
        /* ensure if allow_multiple_emails setting is false, prevent duplicate emails */
        } else if (!$this->modx->getOption('allow_multiple_emails',null,false)) {
            /** @var modUserProfile $emailTaken */
            $emailTaken = $this->modx->getObject('modUserProfile',array('email' => $email));
            if ($emailTaken) {
                $this->validator->addError($emailField,$this->modx->lexicon('register.email_taken',array('email' => $email)));
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Check for a POST submission
     * @return void
     */
    public function checkForPost() {
        $this->hasPosted = !empty($_POST) && (empty($this->scriptProperties['submitVar']) || !empty($_POST[$this->scriptProperties['submitVar']]));
    }

    /**
     * Do any pre-processing before POST
     * @return void
     */
    public function preLoad() {
        $preHooks = $this->getProperty('preHooks','');
        
        /* if using recaptcha, load recaptcha html */
        if (strpos($preHooks,'recaptcha') !== false) {
            $this->loadReCaptcha();
        }

        /* if using math hook, load default placeholders */
        if (strpos($preHooks,'math') !== false && !$this->hasPosted) {
            $this->preLoadMath();
        }
    }

    /**
     * Pre-Load the data values for the math hook
     * @return void
     */
    public function preLoadMath() {
        $mathMaxRange = $this->getProperty('mathMaxRange',100);
        $mathMinRange = $this->getProperty('mathMinRange',10);
        $op1 = rand($mathMinRange,$mathMaxRange);
        $op2 = rand($mathMinRange,$mathMaxRange);
        if ($op2 > $op1) { $t = $op2; $op2 = $op1; $op1 = $t; } /* swap so we always get positive #s */
        $operators = array('+','-');
        $operator = rand(0,1);
        $this->modx->setPlaceholders(array(
            $this->getProperty('mathOp1Field','op1') => $op1,
            $this->getProperty('mathOp2Field','op2') => $op2,
            $this->getProperty('mathOperatorField','operator') => $operators[$operator],
        ),$this->getProperty('placeholderPrefix',''));
    }

    public function loadReCaptcha() {
        $recaptcha = $this->modx->getService('recaptcha','reCaptcha',$this->login->config['modelPath'].'recaptcha/');
        if ($recaptcha instanceof reCaptcha) {
            $this->modx->lexicon->load('login:recaptcha');
            $recaptchaTheme = $this->getProperty('recaptchaTheme','clean');
            $recaptchaWidth = $this->getProperty('recaptchaWidth',500);
            $recaptchaHeight = $this->getProperty('recaptchaHeight',300);
            $html = $recaptcha->getHtml($recaptchaTheme,$recaptchaWidth,$recaptchaHeight);
            $this->modx->setPlaceholder($this->getProperty('placeholderPrefix','').'recaptcha_html',$html);
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Register] '.$this->modx->lexicon('register.recaptcha_err_load'));
        }
    }

}
return 'LoginRegisterController';