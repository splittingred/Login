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
 * Default UpdateProfile snippet properties
 *
 * @package login
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'submitVar',
        'desc' => 'prop_updateprofile.submitvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'redirectToLogin',
        'desc' => 'prop_updateprofile.redirecttologin_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'reloadOnSuccess',
        'desc' => 'prop_updateprofile.reloadonsuccess_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'syncUsername',
        'desc' => 'prop_updateprofile.syncusername_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'emailField',
        'desc' => 'prop_updateprofile.emailfield_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'email',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'useExtended',
        'desc' => 'prop_updateprofile.useextended_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'excludeExtended',
        'desc' => 'prop_updateprofile.excludeextended_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'allowedFields',
        'desc' => 'prop_updateprofile.allowedfields_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'allowedExtendedFields',
        'desc' => 'prop_updateprofile.allowedextendedfields_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'preHooks',
        'desc' => 'prop_updateprofile.prehooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'postHooks',
        'desc' => 'prop_updateprofile.posthooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'placeholderPrefix',
        'desc' => 'prop_updateprofile.placeholderprefix_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
);

return $properties;