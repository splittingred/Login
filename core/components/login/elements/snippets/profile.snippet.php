<?php
/**
 * Profile
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
 * MODx Profile Snippet.
 *
 * Sets Profile data for a user to placeholders
 *
 * @author Shaun McCormick <shaun@modx.com>
 * @copyright Copyright &copy; 2010
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 */
$login = $modx->getService('login','Login',$modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/',$scriptProperties);
if (!($login instanceof Login)) return '';
$modx->lexicon->load('login:profile');

/* setup default properties */
$prefix = $modx->getOption('prefix',$scriptProperties,'');
$user = $modx->getOption('user',$scriptProperties,'');

/* verify authenticated status if no user specified */
if (empty($user) && !$modx->user->hasSessionContext($modx->context->get('key'))) {
    return '';
/* specifying a specific user, so try and get it */
} else if (!empty($user)) {
    $username = $user;
    $userNum = (int)$user;
    $c = array();
    if (!empty($userNum)) {
        $c['id'] = $userNum;
    } else {
        $c['username'] = $username;
    }
    $user = $modx->getObject('modUser',$c);
    if (!$user) {
        $modx->log(modX::LOG_LEVEL_ERROR,'Could not find user: '.$username);
        return '';
    }
/* just use current user */
} else {
    $user =& $modx->user;
}


/* get profile */
$profile = $user->getOne('Profile');
if (empty($profile)) {
    $modx->log(modX::LOG_LEVEL_ERROR,'Could not find profile for user: '.$modx->user->get('username'));
    return '';
}

$placeholders = array_merge($profile->toArray(),$user->toArray());
/* add extended fields to placeholders */
if ($modx->getOption('useExtended',$scriptProperties,true)) {
    $extended = $profile->get('extended');
    if (!empty($extended) && is_array($extended)) {
        $placeholders = array_merge($extended,$placeholders);
    }
}
unset($placeholders['password'],$placeholders['cachepwd']);
/* now set placeholders */
$modx->toPlaceholders($placeholders,$prefix,'');
return '';