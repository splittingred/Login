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
        'desc' => 'The var to check for to load the Register functionality. If empty or set to false, Register will process the form on all POST requests.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
    array(
        'name' => 'redirectToLogin',
        'desc' => 'If a user is not logged in and accesses this Resource, redirect them to the Unauthorized Page.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'reloadOnSuccess',
        'desc' => 'If true, the page will redirect to itself with a GET parameter to prevent double-postbacks. If false, it will simply set a success placeholder.',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => true,
    ),
    array(
        'name' => 'syncUsername',
        'desc' => 'If set to a column name in the Profile, UpdateProfile will attempt to sync the username to this field after a successful save.',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
    ),
);

return $properties;