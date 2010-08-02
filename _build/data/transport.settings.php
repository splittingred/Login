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

$settings['recaptcha.public_key']= $modx->newObject('modSystemSetting');
$settings['recaptcha.public_key']->fromArray(array(
    'key' => 'recaptcha.public_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);

$settings['recaptcha.private_key']= $modx->newObject('modSystemSetting');
$settings['recaptcha.private_key']->fromArray(array(
    'key' => 'recaptcha.private_key',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);

$settings['recaptcha.use_ssl']= $modx->newObject('modSystemSetting');
$settings['recaptcha.use_ssl']->fromArray(array(
    'key' => 'recaptcha.use_ssl',
    'value' => false,
    'xtype' => 'combo-boolean',
    'namespace' => 'recaptcha',
    'area' => 'reCaptcha',
),'',true,true);


return $settings;