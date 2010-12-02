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
 * MODx Confirm Register Activation Snippet. Snippet to place on an activation
 * page that the user using the Register snippet would be sent to via the
 * activation email.
 *
 * @package login
 */
$model_path = $modx->getOption('core_path').'components/login/model/login/';
$Login = $modx->getService('login','Login',$model_path,$scriptProperties);
$modx->lexicon->load('login:register');

/* get user from query params */
if (empty($_REQUEST['lp']) || empty($_REQUEST['lu'])) $modx->sendErrorPage();
$username = base64_decode(urldecode($_REQUEST['lu']));
$password = base64_decode(urldecode($_REQUEST['lp']));

/* validate we have correct user */
$user = $modx->getObject('modUser',array('username' => $username));
if ($user == null) { $modx->sendErrorPage(); }
if ($user->get('active')) { $modx->sendErrorPage(); }

/* validate password to prevent middleman attacks */
$modx->getService('registry', 'registry.modRegistry');
$modx->registry->addRegister('login','registry.modFileRegister');
$modx->registry->login->connect();
$modx->registry->login->subscribe('/useractivation/'.$user->get('username'));
$msgs = $modx->registry->login->read();
if (empty($msgs)) $modx->sendErrorPage();
$found = false;
foreach ($msgs as $msg) {
    if ($msg == $password) $found = true;
}
if (!$found) $modx->sendErrorPage();

/* invoke OnBeforeUserActivateEvent, if result returns anything, do not proceed */
$result = $modx->invokeEvent('OnBeforeUserActivate',array(
    'user' => &$user,
));
if (!empty($result)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Register] OnBeforeUserActivate event prevented activation for "'.$user->get('username').'" by returning false.');
    $modx->sendErrorPage();
}

/* activate user */
$user->set('active',1);
$user->set('cachepwd','');
if (!$user->save()) {
    $modx->log(modX::LOG_LEVEL_ERROR,'[Register] Could not save activated user: '.$user->get('username'));
    return '';
}

/* invoke OnUserActivate event */
$modx->invokeEvent('OnUserActivate',array(
    'user' => &$user,
));

/* if wanting to redirect after confirmed registration (for shopping carts)
 * Also allow &redirectBack parameter sent in confirmation email to redirect
 * to a form requiring registration
 */
$redirectBack = $modx->getOption('redirectBack',$_REQUEST,$modx->getOption('redirectBack',$scriptProperties,''));
$redirectTo = !empty($scriptProperties['redirectTo']) ? $scriptProperties['redirectTo'] : $redirectBack;
if (!empty($redirectTo)) {
    /* allow custom redirection params */
    $redirectParams = $modx->getOption('redirectParams',$scriptProperties,'');
    if (!empty($redirectParams)) $redirectParams = $modx->fromJSON($redirectParams);
    if (empty($redirectParams) || !is_array($redirectParams)) $redirectParams = array();

    /* handle persist params from Register snippet */
    $persistParams = $_GET;
    unset($persistParams['lp'],$persistParams['lu']);
    $persistParams['username'] = $user->get('username');
    $persistParams['userid'] = $user->get('id');    
    $redirectParams = array_merge($redirectParams,$persistParams);

    /* redirect user */
    $url = $modx->makeUrl($redirectTo,'',$redirectParams);
    $modx->sendRedirect($url);
}

return '';