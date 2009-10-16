<?php
/**
 * ForgotPassword
 *
 * Copyright 2009 by Jason Coward <jason@collabpad.com> and Shaun McCormick
 * <shaun@collabpad.com>
 *
 * ForgotPassword is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ForgotPassword is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ForgotPassword; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * MODx ForgotPassword Snippet
 *
 * @author Jason Coward <jason@collabpad.com>
 * @author Shaun McCormick <shaun@collabpad.com>
 * @copyright Copyright &copy; 2009
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 */
$model_path = $modx->getOption('core_path').'components/login/model/login/';
$Login = $modx->getService('login','Login',$model_path,$scriptProperties);

$modx->lexicon->load('login:forgotpassword');

/* set default properties */
$tpl = !empty($tpl) ? $tpl : 'lgnForgotPassTpl';
$tplType = !empty($tplType) ? $tplType : 'modChunk';
$sentTpl = !empty($sentTpl) ? $sentTpl : 'lgnForgotPassSentTpl';
$sentTplType = !empty($sentTplType) ? $sentTplType : 'modChunk';
$emailTpl = !empty($emailTpl) ? $emailTpl : 'lgnForgotPassEmail';
$emailTplType = !empty($emailTplType) ? $emailTplType : 'modChunk';
$resetResourceId = !empty($resetResourceId) ? $resetResourceId : 1;

/* get the request URI */
$phs = array(
    'loginfp.request_uri' => empty($_POST['request_uri']) ? $Login->getRequestURI() : $_POST['request_uri'],
);

if (!empty($_POST['login_fp_service'])) {
    $success = false;
    $field = 'username';
    $alias = 'modUser';
    if (empty($_POST['username']) && !empty($_POST['email'])) {
        $field = 'email';
        $alias = 'modUserProfile';
    }

    /* get the user dependent on the retrieval method */
    $user = $Login->getUserByField($field,$_POST[$field],$alias);
    if ($user == null) {
        $phs['loginfp.errors'] = $modx->lexicon('login.user_err_nf_'.$field);
    } else {
        $phs['email'] = $user->get('email');

        /* generate a password and encode it and the username into the url */
        $pword = $Login->generatePassword();
        $confirmParams = 'lp='.urlencode(base64_encode($pword));
        $confirmParams .= '&lu='.urlencode(base64_encode($user->get('username')));
        $confirmUrl = MODX_SITE_URL.$modx->makeUrl($resetResourceId,'',$confirmParams);

        /* set the email properties */
        $emailProperties = $user->toArray();
        $emailProperties['confirmUrl'] = $confirmUrl;
        $emailProperties['password'] = $pword;
        $emailProperties['tpl'] = $emailTpl;
        $emailProperties['tplType'] = $emailTplType;

        /* now set new password to cache to prevent middleman attacks */
        $modx->cacheManager->set('login/resetpassword/'.$user->get('username'),$pword);

        $subject = $modx->getOption('login.forgot_password_email_subject',$scriptProperties,$modx->lexicon('login.forgot_password_email_subject'));
        $Login->sendEmail($user->get('email'),$user->get('username'),$subject,$emailProperties);
        $tpl = $sentTpl;
    }
}

if (!empty($_POST)) {
    foreach ($_POST as $k => $v) { $phs['loginfp.post.'.$k] = $v; }
}

$output = $Login->getChunk($tpl,$phs,$tplType);

return $output;