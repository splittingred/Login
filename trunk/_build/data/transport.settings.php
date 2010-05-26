<?php
/**
 * @package login
 */
$settings = array();

$settings['login.forgot_password_email_subject']= $modx->newObject('modSystemSetting');
$settings['login.forgot_password_email_subject']->fromArray(array(
    'key' => 'login.forgot_password_email_subject',
    'value' => 'Password Retrieval Email',
    'xtype' => 'textfield',
    'namespace' => 'login',
    'area' => 'security',
),'',true,true);

return $settings;