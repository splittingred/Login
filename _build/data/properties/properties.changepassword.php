<?php
/**
 * Login
 *
 * Copyright 2010 by Shaun McCormick <shaun@modx.com>
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
 * Default ChangePassword snippet properties
 *
 * @package login
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'submitVar',
        'desc' => 'prop_changepassword.submitvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'logcp-submit',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'fieldOldPassword',
        'desc' => 'prop_changepassword.fieldoldpassword_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'password_old',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'fieldNewPassword',
        'desc' => 'prop_changepassword.fieldnewpassword_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'password_new',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'fieldConfirmNewPassword',
        'desc' => 'prop_changepassword.fieldconfirmnewpassword_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'password_new_confirm',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'preHooks',
        'desc' => 'prop_changepassword.prehooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'postHooks',
        'desc' => 'prop_changepassword.posthooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'reloadOnSuccess',
        'desc' => 'prop_changepassword.reloadonsuccess_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'successMessage',
        'desc' => 'prop_changepassword.successmessage_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'placeholderPrefix',
        'desc' => 'prop_changepassword.placeholderprefix_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'logcp.',
        'lexicon' => 'login:properties',
    ),
);

return $properties;