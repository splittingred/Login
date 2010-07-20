<?php
/**
 * Login
 *
 * Copyright 2010 by Jason Coward <jason@modxcms.com> and Shaun McCormick
 * <shaun@modxcms.com>
 *
 * Login is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Login is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Login; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * Update the user's profile
 *
 * @package login
 * @subpackage processors
 */
$profile = $modx->user->getOne('Profile');
if (empty($profile)) return $modx->lexicon('login.profile_err_nf');

/* set fields */
$profile->fromArray($_POST);

/* sync username if setting is set */
$syncUsername = $modx->getOption('syncUsername',$scriptProperties,false);
$oldUsername = $modx->user->get('username');
$usernameChanged = false;
if (!empty($syncUsername)) {
    $newUsername = $profile->get($syncUsername);
    if (!empty($newUsername) && strcmp($newUsername,$oldUsername) == 0) {
        $alreadyExists = $modx->getCount('modUser',array('username' => $newUsername));
        if (!empty($alreadyExists)) {
            return $modx->lexicon('login.username_err_ae');
        }
        $modx->user->set('username',$newUsername);
        $usernameChanged = $modx->user->save();
    }
}

/* if save is unsuccessful */
if ($profile->save() == false) {
    /* first revert username change */
    if ($usernameChanged) {
        $modx->user->set('username',$oldUsername);
        $modx->user->save();
    }
    /* then return error */
    return $modx->lexicon('login.profile_err_save');
}

/* return success */
$successMsg = $modx->getOption('successMsg',$scriptProperties,$modx->lexicon('login.profile_updated'));
$modx->toPlaceholder('error.message',$successMsg);

return true;
