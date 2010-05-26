<?php
/**
 * Register
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * MODx Register Snippet.
 *
 * Handles User registrations.
 *
 * @author Shaun McCormick <shaun@collabpad.com>
 * @copyright Copyright &copy; 2009
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 */
$login = $modx->getService('login','Login',$modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/',$scriptProperties);
if (!($login instanceof Login)) return '';
$modx->lexicon->load('login:updateprofile');

/* set default properties */
$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-updprof-btn');
$redirectToLogin = $modx->getOption('redirectToLogin',$scriptProperties,true);
$reloadOnSuccess = $modx->getOption('reloadOnSuccess',$scriptProperties,true);

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
$modx->toPlaceholders($placeholders);

/* if success */
if (!empty($_REQUEST['updpsuccess'])) {
    $modx->setPlaceholder('login.update_success',true);
}

if (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]))) {
    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST);

    if (empty($login->validator->errors)) {
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
    $modx->toPlaceholders($login->validator->errors,'error');
    $modx->toPlaceholders($fields);
}

return '';


