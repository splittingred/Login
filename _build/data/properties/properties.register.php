<?php
/**
 * Login
 *
 * Copyright 2009 by Jason Coward <jason@collabpad.com> and Shaun McCormick
 * <shaun@collabpad. com>
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
        'desc' => 'The var to check for to load the Register functionality. If empty or set to false, Register will process the form on all POST requests.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'usergroups',
        'desc' => 'Optional. A comma-separated list of User Group names or IDs to add the newly-registered User to.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'submittedResourceId',
        'desc' => 'If set, will redirect to the specified Resource after the User submits the registration form.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'successMsg',
        'desc' => 'Optional. If not redirecting using the submittedResourceId parameter, will display this message instead.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'activation',
        'desc' => 'Whether or not to require activation for proper registration. If true, users will not be marked active until they have activated their account. Defaults to true. Will only work if the registration form passes an email field.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'activationttl',
        'desc' => 'Number of minutes until the activation email expires. Defaults to 3 hours.',
        'type' => 'textfield',
        'options' => '',
        'value' => '180',
    ),
    array(
        'name' => 'activationResourceId',
        'desc' => 'The Resource ID where the ConfirmRegister snippet for activation is located.',
        'type' => 'textfield',
        'options' => '',
        'value' => 1,
    ),
    array(
        'name' => 'activationEmailSubject',
        'desc' => 'The subject of the activation email.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'activationEmailTplType',
        'desc' => 'The type of tpls being provided for the activation email.',
        'type' => 'list',
        'options' => array(
            array('name' => 'Chunk','value' => 'modChunk'),
            array('name' => 'File','value' => 'file'),
            array('name' => 'Inline','value' => 'inline'),
            array('name' => 'Embedded','value' => 'embedded'),
        ),
        'value' => 'modChunk',
    ),
    array(
        'name' => 'activationEmailTpl',
        'desc' => 'The activation email tpl.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnActivateEmailTpl',
    ),
);

return $properties;