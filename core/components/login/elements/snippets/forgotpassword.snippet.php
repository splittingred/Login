<?php
/**
 * ForgotPassword
 *
 * Copyright 2010 by Jason Coward <jason@modx.com> and Shaun McCormick
 * <shaun@modx.com>
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
 * MODx ForgotPassword Snippet. Displays a form for users who have forgotten
 * their password and gives them the ability to retrieve it.
 *
 * @package login
 */
$model_path = $modx->getOption('core_path').'components/login/model/login/';
$login = $modx->getService('login','Login',$model_path,$scriptProperties);

$modx->lexicon->load('login:forgotpassword');

/* set default properties */
$tpl = !empty($tpl) ? $tpl : 'lgnForgotPassTpl';
$tplType = !empty($tplType) ? $tplType : 'modChunk';
$sentTpl = !empty($sentTpl) ? $sentTpl : 'lgnForgotPassSentTpl';
$sentTplType = !empty($sentTplType) ? $sentTplType : 'modChunk';
$emailTpl = !empty($emailTpl) ? $emailTpl : 'lgnForgotPassEmail';
$emailTplType = !empty($emailTplType) ? $emailTplType : 'modChunk';
$emailSubject = !empty($emailSubject) ? $emailSubject : '';
$resetResourceId = !empty($resetResourceId) ? $resetResourceId : 1;
$redirectTo = $modx->getOption('redirectTo',$scriptProperties,false);
$redirectParams = $modx->getOption('redirectParams',$scriptProperties,'');
$preHooks = $modx->getOption('preHooks',$scriptProperties,'');

/* get the request URI */
$phs = array(
    'loginfp.request_uri' => empty($_POST['request_uri']) ? $login->getRequestURI() : $_POST['request_uri'],
);

if (!empty($_POST['login_fp_service'])) {
    $success = false;
    $field = 'username';
    $alias = 'modUser';
    if (empty($_POST['username']) && !empty($_POST['email'])) {
        $field = 'email';
        $alias = 'Profile';
    }
    foreach ($_REQUEST as $k => $v) {
        $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93'),$v);
    }
    $login->loadHooks('fpPreHooks');
    $login->fpPreHooks->loadMultiple($preHooks,$fields,array(
        'mode' => Login::MODE_FORGOT_PASSWORD,
    ));
    /* process prehooks */
    if (!empty($login->fpPreHooks->errors)) {
        $modx->toPlaceholders($login->fpPreHooks->errors,$errorPrefix);

        $errorMsg = $login->fpPreHooks->getErrorMessage();
        $errorOutput = $modx->parseChunk($errTpl, array('msg' => $errorMsg));
        $modx->setPlaceholder('errors',$errorOutput);

    } else {
        if (!empty($login->fpPreHooks->fields)) {
            $fields = $login->fpPreHooks->fields;
        }

        /* if the prehook didn't set the user info, find it by email/username */
        if (empty($fields[Login::FORGOT_PASSWORD_EXTERNAL_USER])) {
            /* get the user dependent on the retrieval method */
            $user = $login->getUserByField($field,$fields[$field],$alias);
            $fields = array_merge($fields,$user->toArray());
            $profile = $user->getOne('Profile');
            if ($profile) { /* merge in profile */
                $fields = array_merge($profile->toArray(),$fields);
            }
        }
        
        if ($user == null) {
            $phs['loginfp.errors'] = $modx->lexicon('login.user_err_nf_'.$field);
        } else {
            $phs['email'] = $fields['email'];

            /* generate a password and encode it and the username into the url */
            $pword = $login->generatePassword();
            $confirmParams = array(
                'lp' => urlencode(base64_encode($pword)),
                'lu' => urlencode(base64_encode($fields['username']))
            );
            $confirmUrl = $modx->makeUrl($resetResourceId,'',$confirmParams,'full');

            /* set the email properties */
            $emailProperties = $fields;
            $emailProperties['confirmUrl'] = $confirmUrl;
            $emailProperties['password'] = $pword;
            $emailProperties['tpl'] = $emailTpl;
            $emailProperties['tplType'] = $emailTplType;

            /* now set new password to cache to prevent middleman attacks */
            $modx->cacheManager->set('login/resetpassword/'.$fields['username'],$pword);

            $subject = !empty($emailSubject) ? $emailSubject : $modx->getOption('login.forgot_password_email_subject',$scriptProperties,$modx->lexicon('login.forgot_password_email_subject'));
            $login->sendEmail($fields['email'],$fields['username'],$subject,$emailProperties);
            $tpl = $sentTpl;

            /* if redirecting, do so here */
            if (!empty($redirectTo)) {
                if (!empty($redirectParams)) $redirectParams = $modx->fromJSON($redirectParams);
                $url = $modx->makeUrl($redirectTo,'',$redirectParams,'full');
                $modx->sendRedirect($url);
            }
        }
    }
}
if (!empty($_POST)) {
    foreach ($_POST as $k => $v) {
        $phs['loginfp.post.'.$k] = str_replace(array('[',']'),array('&#91;','&#93'),$v);
    }
}

$output = $login->getChunk($tpl,$phs,$tplType);

return $output;