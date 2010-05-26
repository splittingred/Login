<?php
/**
 * Update the user's profile
 *
 * @package login
 * @subpackage processors
 */
$profile = $modx->user->getOne('Profile');
if (empty($profile)) return $modx->lexicon('login.profile_err_nf');

$profile->fromArray($_POST);
if ($profile->save() == false) {
    return $modx->lexicon('login.profile_err_save');
}

$successMsg = $modx->getOption('successMsg',$scriptProperties,$modx->lexicon('login.profile_updated'));
$modx->toPlaceholder('error.message',$successMsg);

return true;
