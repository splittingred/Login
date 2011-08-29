<?php
/**
 * ChangePassword snippet
 *
 * @package login
 **/
$login = $modx->getService('login','Login',$modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/',$scriptProperties);
if (!($login instanceof Login)) return '';

$controller = $login->loadController('ResetPassword');
$output = $controller->run($scriptProperties);
return $output;


$error = false;
if (!empty($_POST) && isset($_POST[$submitVar])) {
    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST,$validate);
    foreach ($fields as $k => $v) {
        $fields[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
    }
    if (!empty($submitVar)) unset($fields[$submitVar]);
    $errors = $login->validator->errors;

    if (empty($errors)) {
        /* do prehooks */
        $login->loadHooks('preHooks');
        $login->preHooks->loadMultiple($preHooks,$fields,array(
            'user' => &$modx->user,
            'submitVar' => $submitVar,
            'reloadOnSuccess' => $reloadOnSuccess,
            'fieldOldPassword' => $fieldOldPassword,
            'fieldNewPassword' => $fieldNewPassword,
            'fieldConfirmNewPassword' => $fieldConfirmNewPassword,
        ));
        if (!empty($login->preHooks->fields)) {
            $fields = $login->preHooks->fields;
        }

        /* process preHooks */
        if (!empty($login->preHooks->errors)) {
            $errors = $login->preHooks->errors;
            $modx->setPlaceholders($errors,$placeholderPrefix.'error.');

            $errorMsg = $login->preHooks->getErrorMessage();
            $modx->setPlaceholder($placeholderPrefix.'error_message',$errorMsg);

        } else {
            /* if changing the password */
            if ($validateOldPassword) {
                $version = $modx->getVersionData();
                if (version_compare($version['full_version'],'2.1.0','>=')) {
                    if (empty($fields[$fieldOldPassword]) || !$modx->user->passwordMatches($fields[$fieldOldPassword])) {
                        $errors[$fieldOldPassword] = $modx->lexicon('login.password_invalid_old');
                    }
                } else {
                    if (empty($fields[$fieldOldPassword]) || md5($fields[$fieldOldPassword]) != $modx->user->get('password')) {
                        $errors[$fieldOldPassword] = $modx->lexicon('login.password_invalid_old');
                    }
                }
            }
            $minLength = $modx->getOption('password_min_length',null,8);
            if (empty($fields[$fieldNewPassword]) || strlen($fields[$fieldNewPassword]) < $minLength) {
                $errors[$fieldNewPassword] = $modx->lexicon('login.password_too_short',array('length' => $minLength));
            }

            /* if using confirm, ensure they match */
            if (!empty($fieldConfirmNewPassword)) {
                if (empty($fields[$fieldConfirmNewPassword]) || $fields[$fieldNewPassword] != $fields[$fieldConfirmNewPassword]) {
                    $errors[$fieldConfirmNewPassword] = $modx->lexicon('login.password_no_match');
                }
            }

            if (empty($errors)) {
                /* attempt to change the password */
                $success = $modx->user->changePassword($fields[$fieldNewPassword],$fields[$fieldOldPassword]);
                if (!$success) {
                    /* for some reason it failed (possibly a plugin) so send error message */
                    $modx->setPlaceholder($placeholderPrefix.$fieldNewPassword,$modx->lexicon('login.password_err_change'));

                } else {
                    /* do post-update hooks */
                    $postHooks = $modx->getOption('postHooks',$scriptProperties,'');
                    $login->loadHooks('postHooks');
                    $fields['changepassword.user'] = &$modx->user;
                    $fields['changepassword.profile'] =& $profile;
                    $fields['changepassword.fieldOldPassword'] = $fieldOldPassword;
                    $fields['changepassword.fieldNewPassword'] = $fieldNewPassword;
                    $fields['changepassword.fieldConfirmNewPassword'] = $fieldConfirmNewPassword;
                    $login->postHooks->loadMultiple($postHooks,$fields);

                    /* process post hooks errors */
                    if (!empty($login->postHooks->errors)) {
                        $modx->setPlaceholders($login->postHooks->errors,$placeholderPrefix.'error.');

                        $errorMsg = $login->postHooks->getErrorMessage();
                        $modx->setPlaceholder($placeholderPrefix.'error_message',$errorMsg);
                    }

                    if ($reloadOnSuccess) {
                        /* if reloading the page after success */
                        $reloadOnSuccessVar = $modx->getOption('reloadOnSuccessVar',$scriptProperties,'logcp-success');
                        $url = $modx->makeUrl($modx->resource->get('id'),'',array($reloadOnSuccessVar => 1),'full');
                        $modx->sendRedirect($url);

                    } else {
                        /* otherwise just spit out a success message/placeholder */
                        $modx->setPlaceholder($placeholderPrefix.'passwordChanged',true);
                        if (!empty($successMessage)) {
                            $modx->setPlaceholder($placeholderPrefix.'successMessage',$successMessage);
                        }
                        return '';
                    }
                }
            }
        }
    }
    $modx->setPlaceholders($errors,$placeholderPrefix.'error.');
    $modx->setPlaceholders($fields,$placeholderPrefix);
}

return '';