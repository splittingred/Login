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
    public $success = false;

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
            'activationEmailTplAlt' => '',
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
            'validatePassword' => true,
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
        $fields = $this->validateFields();
        $this->dictionary->reset();
        $this->dictionary->fromArray($fields);

        $this->validateUsername();
        if ($this->getProperty('validatePassword',true,'isset')) {
            $this->validatePassword();
        }
        if ($this->getProperty('ensurePasswordStrength',false,'isset')) {
            $this->ensurePasswordStrength();
        }
        if ($this->getProperty('generatePassword',false,'isset')) {
            $this->generatePassword();
        }
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
                $result = $this->runProcessor('register');
                if ($result !== true) {
                    $this->modx->setPlaceholder($placeholderPrefix.'error.message',$result);
                } else {
                    $this->success = true;
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
            $values = $this->preHooks->getValues();
            if (!empty($values)) {
                $this->dictionary->fromArray($values);
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
        if (empty($username) && !$this->validator->hasErrorsInField($usernameField)) {
            $this->validator->addError($usernameField,$this->modx->lexicon('register.field_required'));
            $success = false;
        } else {
            /* make sure username isnt taken */
            /** @var modUser $alreadyExists */
            $alreadyExists = $this->modx->getObject('modUser',array('username' => $username));
            if ($alreadyExists) {
                $cachePwd = $alreadyExists->get('cachepwd');
                if ($this->getProperty('removeExpiredRegistrations',true,'isset') && $alreadyExists->get('active') == 0 && !empty($cachePwd)) {
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

    public function getPassword() {
        $passwordField = $this->getProperty('passwordField','password');
        $password = $this->dictionary->get($passwordField);
        if ($this->getProperty('trimPassword',true,'isset')) {
            $password = trim($password);
        }
        return $password;
    }

    /**
     * Validate the password field and trim it if specified
     *
     * @return boolean
     */
    public function validatePassword() {
        $password = $this->getPassword();
        $passwordField = $this->getProperty('passwordField','password');
        $success = true;

        /* ensure password field isn't empty */
        if (empty($password) && !$this->validator->hasErrorsInField($passwordField)) {
            $this->validator->addError($passwordField,$this->modx->lexicon('register.field_required'));
            $success = false;
        }
        return $success;
    }

    /**
     * Automatically generate a password for the user
     * @return string
     */
    public function generatePassword() {
        $classKey = $this->dictionary->get('class_key');
        if (empty($classKey)) $classKey = 'modUser';
        /** @var modUser $user */
        $user = $this->modx->newObject($classKey);
        $password = $user->generatePassword();
        $this->dictionary->set($this->getProperty('passwordField','password'),$password);
        $this->dictionary->set('password_confirm',$password);
        return $password;
    }

    /**
     * Validate the email address, and ensure it is not empty or already taken
     * @return boolean
     */
    public function validateEmail() {
        $emailField = $this->getProperty('emailField','email');
        $email = $this->dictionary->get($emailField);
        $success = true;

        /* ensure email field isn't empty */
        if (empty($email) && !$this->validator->hasErrorsInField($emailField)) {
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
        /** @var reCaptcha $recaptcha */
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

    /**
     * Algorithm to ensure and suggest password strength
     * @return bool
     */
    public function ensurePasswordStrength() {
        $ensured = false;
        $password = $this->getPassword();
        $passwordField = $this->getProperty('passwordField','password');

        $passwordWordSeparator = $this->getProperty('passwordWordSeparator',' ','isset');
        if (strlen($passwordWordSeparator) == 0) $passwordWordSeparator = ' ';
        $wordCount = $this->getWordsInString($password,$passwordWordSeparator);
        $minimumStrongPasswordWordCount = $this->getProperty('minimumStrongPasswordWordCount',4,'!empty');
        if ($wordCount < $minimumStrongPasswordWordCount || $minimumStrongPasswordWordCount == 0) {
            $passwordStrengthThreshold = $this->getProperty('maximumPossibleStrongerPasswords',25,'!empty');
            if ($passwordStrengthThreshold > 0) {
                $possible = $this->getPossibleStrongerPasswords($password);

                if (count($possible) > $passwordStrengthThreshold) {
                    $ensurePasswordStrengthSuggestions = $this->getProperty('ensurePasswordStrengthSuggestions',5,'!empty');
                    $suggestionIndexes = array_rand($possible,$ensurePasswordStrengthSuggestions);
                    $suggestions = array();
                    foreach ($suggestionIndexes as $idx) {
                        $suggestions[] = $possible[$idx];
                    }
                    $this->validator->addError($passwordField,$this->modx->lexicon('register.use_stronger_password',array(
                        'suggestions' => implode(', ',$suggestions),
                    )));
                }
            } else {
                $ensured = true;
            }
        } else {
            $ensured = true;
        }

        return $ensured;
    }

    public function getWordsInString($str,$separator) {
        return count(explode($separator,$str));
    }


    /** @var array $strongPasswordMap */
    public $strongPasswordMap = array(
        'a' => array('@','A','4'),
        'b' => array('8','B'),
        'c' => array('('),
        'e' => array('3','E'),
        'f' => array('ph'),
        'g' => array('6'),
        'i' => array('1','!','|'),
        'l' => array('1','L'),
        'n' => array('en'),
        'o' => array('0','O'),
        's' => array('$','5'),
        't' => array('7','+'),
        'x' => array('X'),
        'z' => array('2'),
    );

    /**
     * Given a password, find stronger ones
     *
     * @param string $password
     * @return array
     */
    public function getPossibleStrongerPasswords($password) {
        $passwordLength = strlen($password);
        if ($passwordLength == 1) return isset($this->strongPasswordMap[$password]) ? $this->strongPasswordMap[$password] : $password;

        $rest = $this->getPossibleStrongerPasswords(substr($password,1));
        $restLength = count($rest);

        $current = isset($this->strongPasswordMap[$password[0]]) ? $this->strongPasswordMap[$password[0]] : null;
        $currentLength = count($current);
        $result = array();

        if ($current) {
            for ($i=0;$i<$currentLength;$i++) {
                for ($j=0;$j<$restLength;$j++) {
                    $result[] = $current[$i].$rest[$j];
                }
            }
        } else {
            for ($j=0;$j<$restLength;$j++) {
                $result[] = $password[0].$rest[$j];
            }
        }
        return $result;
    }
}
return 'LoginRegisterController';