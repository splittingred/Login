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
$tplTypeOptions = array(
    array('name' => 'opt_register.chunk','value' => 'modChunk'),
    array('name' => 'opt_register.file','value' => 'file'),
    array('name' => 'opt_register.inline','value' => 'inline'),
    array('name' => 'opt_register.embedded','value' => 'embedded'),
);
$properties = array(
    array(
        'name' => 'emailTpl',
        'desc' => 'prop_forgotpassword.emailtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnForgotPassEmail',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'emailTplAlt',
        'desc' => 'prop_forgotpassword.emailtplalt_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'emailTplType',
        'desc' => 'prop_forgotpassword.emailtpltype_desc',
        'type' => 'list',
        'options' => $tplTypeOptions,
        'value' => 'modChunk',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'sentTpl',
        'desc' => 'prop_forgotpassword.senttpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnForgotPassSentTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'sentTplType',
        'desc' => 'prop_forgotpassword.senttpltype_desc',
        'type' => 'list',
        'options' => $tplTypeOptions,
        'value' => 'modChunk',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'tpl',
        'desc' => 'prop_forgotpassword.tpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnForgotPassTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'tplType',
        'desc' => 'prop_forgotpassword.tpltype_desc',
        'type' => 'list',
        'options' => $tplTypeOptions,
        'value' => 'modChunk',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'errTpl',
        'desc' => 'prop_forgotpassword.errtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnErrTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'errTplType',
        'desc' => 'prop_forgotpassword.errtpltype_desc',
        'type' => 'list',
        'options' => $tplTypeOptions,
        'value' => 'modChunk',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'emailSubject',
        'desc' => 'prop_forgotpassword.emailsubject_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'resetResourceId',
        'desc' => 'prop_forgotpassword.resetresourceid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 1,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'redirectTo',
        'desc' => 'prop_forgotpassword.redirectto_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'redirectParams',
        'desc' => 'prop_forgotpassword.redirectparams_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
);

return $properties;