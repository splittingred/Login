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
        'desc' => 'prop_activeusers.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnActiveUser',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'tplType',
        'desc' => 'prop_activeusers.tpltype_desc',
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
        'name' => 'sortBy',
        'desc' => 'prop_activeusers.sortby_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'username',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'sortDir',
        'desc' => 'prop_activeusers.sortdir_desc',
        'type' => 'textfield',
        'options' => array(
            array('name' => 'opt_register.asc','value' => 'ASC'),
            array('name' => 'opt_register.desc','value' => 'DESC'),
        ),
        'value' => 'DESC',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'limit',
        'desc' => 'prop_activeusers.limit_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 10,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'offset',
        'desc' => 'prop_activeusers.offset_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 0,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'classKey',
        'desc' => 'prop_activeusers.classkey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'modUser',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'placeholderPrefix',
        'desc' => 'prop_activeusers.placeholderprefix_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'au.',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'prop_activeusers.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
);

return $properties;