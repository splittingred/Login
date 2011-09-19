<?php
/**
 * @var LoginHooks $hook
 */
/** @var modUser $user */
$user =& $fields['register.user'];
/** @var modUserProfile $profile  */
$profile =& $fields['register.profile'];

$hook->setValue('username',$user->get('username'));
$hook->setValue('email',$profile->get('email'));

$hook->setValues(array(
  'fullname' => 'John Doe',
));
return true;