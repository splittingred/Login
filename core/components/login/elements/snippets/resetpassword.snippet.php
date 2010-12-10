<?php
/**
 * ResetPassword
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
 *
 * ResetPassword is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ResetPassword is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ResetPassword; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * MODx ResetPassword Snippet. Snippet to place on an activation
 * page that the user using the ForgotPassword snippet would be sent to via the
 * reset email.
 *
 * @package login
 */
if (empty($_REQUEST['lp']) || empty($_REQUEST['lu'])) {
    return '';
}
$model_path = $modx->getOption('core_path').'components/login/model/login/';
$Login = $modx->getService('login','Login',$model_path,$scriptProperties);

/* setup default properties */
$tpl = !empty($tpl) ? $tpl : 'lgnResetPassTpl';
$tplType = !empty($tplType) ? $tplType : 'modChunk';
$loginResourceId = !empty($loginResourceId) ? $loginResourceId : 1;
$debug = isset($debug) ? $debug : false;

/* get user from query params */
$username = base64_decode(urldecode($_REQUEST['lu']));
$password = base64_decode(urldecode($_REQUEST['lp']));

/* validate we have correct user */
$user = $modx->getObject('modUser',array('username' => $username));
if ($user == null) return '';

/* validate password to prevent middleman attacks */
$cacheKey = 'login/resetpassword/'.$user->get('username');
$cachePass = $modx->cacheManager->get($cacheKey);
if ($cachePass != $password) return '';
$modx->cacheManager->delete($cacheKey);

/* change password */
$user->set('password',md5($password));
if (!$debug) {
    if ($user->save() == false) return '';
}

$modx->invokeEvent('OnWebChangePassword', array (
    'userid' => $user->get('id'),
    'username' => $user->get('username'),
    'userpassword' => $password,
    'user' => &$user,
    'newpassword' => $password,
));
$modx->invokeEvent('OnUserChangePassword', array (
    'userid' => $user->get('id'),
    'username' => $user->get('username'),
    'userpassword' => $password,
    'user' => &$user,
    'newpassword' => $password,
));

$phs = array(
    'username' => $user->get('username'),
    'loginUrl' => $modx->makeUrl($loginResourceId),
);

$output = $Login->getChunk($tpl,$phs,$tplType);

return $output;