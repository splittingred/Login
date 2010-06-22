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
 * Default ResetPassword snippet properties
 *
 * @package login
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'The reset password message tpl.',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnResetPassTpl',
    ),
    array(
        'name' => 'tplType',
        'desc' => 'The type of tpl being provided. Defaults to a Chunk.',
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
        'name' => 'loginResourceId',
        'desc' => 'The resource to direct users to on successful confirmation.',
        'type' => 'textfield',
        'options' => '',
        'value' => '1',
    ),
);

return $properties;