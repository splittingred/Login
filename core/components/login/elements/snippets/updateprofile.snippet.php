<?php
/**
 * UpdateProfile
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
 * MODx UpdateProfile Snippet. Handles updating of User Profiles.
 *
 * @package login
 */
$login = $modx->getService('login','Login',$modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/',$scriptProperties);
if (!($login instanceof Login)) return '';
$modx->lexicon->load('login:updateprofile');
$modx->lexicon->load('login:register');

/* setup default properties */
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');
$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-updprof-btn');
$redirectToLogin = $modx->getOption('redirectToLogin',$scriptProperties,true);
$reloadOnSuccess = $modx->getOption('reloadOnSuccess',$scriptProperties,true);
$errTpl = $modx->getOption('errTpl',$scriptProperties,'<span class="error">[[+error]]</span>');
$emailField = $modx->getOption('email',$scriptProperties,'email');
$placeholderPrefix = $modx->getOption('placeholderPrefix',$scriptProperties,'');

/* verify authenticated status */
if (!$modx->user->hasSessionContext($modx->context->get('key'))) {
    if ($redirectToLogin) {
        $modx->sendUnauthorizedPage();
    } else {
        return '';
    }
}

/* get profile */
$profile = $modx->user->getOne('Profile');
if (empty($profile)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not find profile for user: '.$modx->user->get('username'));
    return '';
}

$placeholders = $profile->toArray();
/* add extended fields to placeholders */
if ($modx->getOption('useExtended',$scriptProperties,true)) {
    $extended = $profile->get('extended');
    if (!empty($extended) && is_array($extended)) {
        $placeholders = array_merge($extended,$placeholders);
    }
}
$modx->toPlaceholders($placeholders);

/* if success */
if (!empty($_REQUEST['updpsuccess'])) {
    $modx->setPlaceholder('login.update_success',true);
}

if (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]))) {
    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST);
    foreach ($fields as $k => $v) {
        $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
    }
    if (!empty($submitVar)) unset($fields[$submitVar]);
    
    /* if allow_multiple_emails setting is false, prevent duplicate emails */
    if (!empty($fields[$emailField]) && !$modx->getOption('allow_multiple_emails',null,false)) {
        $emailTaken = $modx->getObject('modUserProfile',array(
            'email' => $fields[$emailField],
            'id:!=' => $modx->user->get('id'),
        ));
        if ($emailTaken) {
            $login->validator->errors[$emailField] = $modx->lexicon('login.email_taken',array('email' => $fields[$emailField]));
        }
    }

    if (empty($login->validator->errors)) {
        /* do prehooks */
        $login->loadHooks('preHooks');
        $login->preHooks->loadMultiple($preHooks,$fields,array(
            'submitVar' => $submitVar,
            'redirectToLogin' => $redirectToLogin,
            'reloadOnSuccess' => $reloadOnSuccess,
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
            /* update the profile */
            $result = require_once $login->config['processorsPath'].'update.profile.php';
            if ($result !== true) {
                $modx->toPlaceholder('message',$result,'error');
            } else if ($reloadOnSuccess) {
                $url = $modx->makeUrl($modx->resource->get('id'),'','?updpsuccess=1');
                $modx->sendRedirect($url);
            } else {
                $modx->setPlaceholder('login.update_success',true);
            }
        }
    }
    $errors = array();
    foreach ($login->validator->errors as $key => $error) {
      $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
    }
    $modx->toPlaceholders($login->validator->errors,$placeholderPrefix.'error');
    $modx->toPlaceholders($fields,$placeholderPrefix);
}

return '';


