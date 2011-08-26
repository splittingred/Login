<?php
/**
 * Register
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
 *
 * Register is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Register is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Register; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * MODx Register Snippet. Handles User registrations.
 * 
 * @package login
 */
require_once $modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/login.class.php';
$login = new Login($modx,$scriptProperties);

$controller = $login->loadController('Register');
$output = $controller->run($scriptProperties);
return $output;

/* setup default properties */
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');
$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-register-btn');
$errTpl = $modx->getOption('errTpl',$scriptProperties,'<span class="error">[[+error]]</span>');
$validate = $modx->getOption('validate',$scriptProperties,'');
$placeholderPrefix = $modx->getOption('placeholderPrefix',$scriptProperties,'');

/* see if form has submitted */
$hasPosted = !empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]));

/* if using recaptcha, load recaptcha html */
if (strpos($preHooks,'recaptcha') !== false) {
    $recaptcha = $modx->getService('recaptcha','reCaptcha',$login->config['modelPath'].'recaptcha/');
    if ($recaptcha instanceof reCaptcha) {
        $modx->lexicon->load('login:recaptcha');
        $recaptchaTheme = $modx->getOption('recaptchaTheme',$scriptProperties,'clean');
        $recaptchaWidth = $modx->getOption('recaptchaWidth',$scriptProperties,500);
        $recaptchaHeight = $modx->getOption('recaptchaHeight',$scriptProperties,300);
        $html = $recaptcha->getHtml($recaptchaTheme,$recaptchaWidth,$recaptchaHeight);
        $modx->setPlaceholder($placeholderPrefix.'recaptcha_html',$html);
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Register] '.$this->modx->lexicon('register.recaptcha_err_load'));
    }
}

/* if using math hook, load default placeholders */
if (strpos($preHooks,'math') !== false && !$hasPosted) {
    $mathMaxRange = $modx->getOption('mathMaxRange',$scriptProperties,100);
    $mathMinRange = $modx->getOption('mathMinRange',$scriptProperties,10);
    $op1 = rand($mathMinRange,$mathMaxRange);
    $op2 = rand($mathMinRange,$mathMaxRange);
    if ($op2 > $op1) { $t = $op2; $op2 = $op1; $op1 = $t; } /* swap so we always get positive #s */
    $operators = array('+','-');
    $operator = rand(0,1);
    $modx->setPlaceholders(array(
        $modx->getOption('mathOp1Field',$scriptProperties,'op1') => $op1,
        $modx->getOption('mathOp2Field',$scriptProperties,'op2') => $op2,
        $modx->getOption('mathOperatorField',$scriptProperties,'operator') => $operators[$operator],
    ),$placeholderPrefix);
}

/* check for POST */
if ($hasPosted) {
    $modx->lexicon->load('login:register');

    /* set default properties */
    $usernameField = $modx->getOption('usernameField',$scriptProperties,'username');
    $emailField = $modx->getOption('emailField',$scriptProperties,'email');
    $passwordField = $modx->getOption('passwordField',$scriptProperties,'password');
    $properties = array();

    /* handle validation */
    $modx->loadClass('lgnDictionary',$login->config['modelPath'].'model/login/',true,true);
    $dictionary = new lgnDictionary($login,$_POST);
    $login->loadValidator();
    $fields = $login->validator->validateFields($dictionary,$validate);
    foreach ($fields as $k => $v) {
        $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
    }

    /* ensure username field exists and isn't empty */
    if (empty($fields[$usernameField])) {
        $login->validator->addError($usernameField,$modx->lexicon('register.field_required'));
    } else {
        /* make sure username isnt taken */
        $alreadyExists = $modx->getObject('modUser',array('username' => $fields[$usernameField]));
        if ($alreadyExists) {
            $cachePwd = $alreadyExists->get('cachepwd');
            if ($alreadyExists->get('active') == 0 && !empty($cachePwd)) {
                /* if inactive and has a cachepwd, probably an expired
                 * activation account, so let's remove it
                 * and let user re-register
                 */
                if (!$alreadyExists->remove()) {
                    $modx->log(modX::LOG_LEVEL_ERROR,'[Login] Could not remove old, deactive user with cachepwd.');
                }
            } else {
                $login->validator->addError($usernameField,$modx->lexicon('register.username_taken'));
            }
        }
    }

    /* ensure password field isn't empty */
    if (empty($fields[$passwordField])) {
        $login->validator->addError($passwordField,$modx->lexicon('register.field_required'));
    }
    /* ensure email field isn't empty */
    if (empty($fields[$emailField])) {
        $login->validator->addError($emailField,$modx->lexicon('register.field_required'));
    /* ensure if allow_multiple_emails setting is false, prevent duplicate emails */
    } else if (!$modx->getOption('allow_multiple_emails',null,false)) {
        $emailTaken = $modx->getObject('modUserProfile',array('email' => $fields[$emailField]));
        if ($emailTaken) {
            $login->validator->addError($emailField,$modx->lexicon('register.email_taken',array('email' => $fields[$emailField])));
        }
    }

    if (empty($login->validator->errors)) {
        /* do pre-register hooks */
        $login->loadHooks('preHooks');
        $login->preHooks->loadMultiple($preHooks,$fields,array(
            'submitVar' => $submitVar,
            'usernameField' => $usernameField,
        ));
        if (!empty($login->preHooks->fields)) {
            $fields = $login->preHooks->fields;
        }

        /* process hooks */
        if (!empty($login->preHooks->errors)) {
            $modx->toPlaceholders($login->preHooks->errors,$placeholderPrefix.'error');

            $errorMsg = $login->preHooks->getErrorMessage();
            $modx->setPlaceholder($placeholderPrefix.'error.message',$errorMsg);
        } else {
            /* everything good, go ahead and register */
            $result = require_once $login->config['processorsPath'].'register.php';
            if ($result !== true) {
                $modx->setPlaceholder($placeholderPrefix.'error.message',$result);
            }
        }
    } else {
        $modx->toPlaceholders($login->validator->errors,$placeholderPrefix.'error');
        $modx->setPlaceholder($placeholderPrefix.'validation_error',true);
    }
    $modx->setPlaceholders($fields,$placeholderPrefix);
}

return '';