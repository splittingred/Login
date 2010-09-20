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
 * Default Register snippet properties
 *
 * @package login
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'submitVar',
        'desc' => 'prop_register.submitvar_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'usergroups',
        'desc' => 'prop_register.usergroups_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'submittedResourceId',
        'desc' => 'prop_register.submittedresourceid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'usernameField',
        'desc' => 'prop_register.usernamefield_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'username',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'passwordField',
        'desc' => 'prop_register.passwordfield_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'password',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'emailField',
        'desc' => 'prop_register.emailfield_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'email',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'successMsg',
        'desc' => 'prop_register.successmsg_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'preHooks',
        'desc' => 'prop_register.prehooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'postHooks',
        'desc' => 'prop_register.posthooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'useExtended',
        'desc' => 'prop_register.useextended_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activation',
        'desc' => 'prop_register.activation_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activationttl',
        'desc' => 'prop_register.activationttl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '180',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activationResourceId',
        'desc' => 'prop_register.activationresourceid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 1,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activationEmailSubject',
        'desc' => 'prop_register.activationemailsubject_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activationEmailTplType',
        'desc' => 'prop_register.activationemailtpltype_desc',
        'type' => 'list',
        'options' => array(
            array('name' => 'opt_register.chunk','value' => 'modChunk'),
            array('name' => 'opt_register.file','value' => 'file'),
            array('name' => 'opt_register.inline','value' => 'inline'),
            array('name' => 'opt_register.embedded','value' => 'embedded'),
        ),
        'value' => 'modChunk',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'activationEmailTpl',
        'desc' => 'prop_register.activationemailtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnActivateEmailTpl',
        'lexicon' => 'login:properties',
    ),
    /* recaptcha hook */
    array(
        'name' => 'recaptchaTheme',
        'desc' => 'prop_register.recaptchatheme_desc',
        'type' => 'list',
        'options' => array(
            array('text' => 'opt_register.red','value' => 'red'),
            array('text' => 'opt_register.white','value' => 'white'),
            array('text' => 'opt_register.clean','value' => 'clean'),
            array('text' => 'opt_register.blackglass','value' => 'blackglass'),
        ),
        'value' => 'clean',
        'lexicon' => 'login:properties',
    ),
);

return $properties;