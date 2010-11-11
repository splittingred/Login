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
$corePath = $modx->getOption('login.core_path',$scriptProperties,$modx->getOption('core_path',null,MODX_CORE_PATH).'components/login/');
$login = $modx->getService('login','Login',$corePath.'model/login/',$scriptProperties);
if (!is_object($login) || !($login instanceof Login)) return '';

/* setup default properties */
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');
$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-register-btn');
$errTpl = $modx->getOption('errTpl',$scriptProperties,'<span class="error">[[+error]]</span>');

/* if using recaptcha, load recaptcha html */
if (strpos($preHooks,'recaptcha') !== false) {
    $recaptcha = $modx->getService('recaptcha','reCaptcha',$login->config['modelPath'].'recaptcha/');
    if ($recaptcha instanceof reCaptcha) {
        $modx->lexicon->load('login:recaptcha');
        $recaptchaTheme = $modx->getOption('recaptchaTheme',$scriptProperties,'clean');
        $recaptchaWidth = $modx->getOption('recaptchaWidth',$scriptProperties,500);
        $recaptchaHeight = $modx->getOption('recaptchaHeight',$scriptProperties,300);
        $html = $recaptcha->getHtml($recaptchaTheme,$recaptchaWidth,$recaptchaHeight);
        $modx->setPlaceholder('register.recaptcha_html',$html);
    } else {
        $modx->log(modX::LOG_LEVEL_ERROR,'[Register] '.$this->modx->lexicon('register.recaptcha_err_load'));
    }
}

/* check for POST */
if (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]))) {
    $modx->lexicon->load('login:register');

    /* set default properties */
    $usernameField = $modx->getOption('usernameField',$scriptProperties,'username');
    $emailField = $modx->getOption('emailField',$scriptProperties,'email');
    $passwordField = $modx->getOption('passwordField',$scriptProperties,'password');
    $properties = array();

    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST);

    if (empty($fields[$usernameField])) {
        $login->validator->errors[$usernameField] = $modx->lexicon('register.field_required');
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
                $alreadyExists->remove();
            } else {
                $login->validator->errors[$usernameField] = $modx->lexicon('register.username_taken');
            }
        }
    }
    
    if (empty($fields[$passwordField])) {
        $login->validator->errors[$passwordField] = $modx->lexicon('register.field_required');
    }
    if (empty($fields[$emailField])) {
        $login->validator->errors[$emailField] = $modx->lexicon('register.field_required');
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
            $errors = array();
            foreach ($login->preHooks->errors as $key => $error) {
                $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
            }
            $modx->toPlaceholders($errors,'error');

            $errorMsg = $login->preHooks->getErrorMessage();
            $modx->toPlaceholder('message',$errorMsg,'error');
        } else {
            /* everything good, go ahead and register */
            $result = require_once $login->config['processorsPath'].'register.php';
            if ($result !== true) {
                $modx->toPlaceholder('message',$result,'error');
            }
        }
    } else {
        $errors = array();
        foreach ($login->validator->errors as $key => $error) {
            $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
        }
        $modx->toPlaceholders($errors,'error');
    }
    $modx->toPlaceholders($fields);
}

return '';