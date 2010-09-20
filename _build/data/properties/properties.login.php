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
 * Default Login snippet properties
 *
 * @package login
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'actionKey',
        'desc' => 'prop_login.actionkey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'service',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'loginKey',
        'desc' => 'prop_login.loginkey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'login',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'logoutKey',
        'desc' => 'prop_login.logoutkey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'logout',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'tplType',
        'desc' => 'prop_login.tpltype_desc',
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
        'name' => 'loginTpl',
        'desc' => 'prop_login.logintpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnLoginTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'logoutTpl',
        'desc' => 'prop_login.logouttpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnLogoutTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'preHooks',
        'desc' => 'prop_login.prehooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => false,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'postHooks',
        'desc' => 'prop_login.posthooks_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => false,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'errTpl',
        'desc' => 'prop_login.errtpl_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'lgnErrTpl',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'errTplType',
        'desc' => 'prop_login.errtpltype_desc',
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
        'name' => 'logoutResourceId',
        'desc' => 'prop_login.logoutresourceid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'loginResourceId',
        'desc' => 'prop_login.loginresourceid_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '0',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'loginMsg',
        'desc' => 'prop_login.loginmsg_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'logoutMsg',
        'desc' => 'prop_login.logoutmsg_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'redirectToPrior',
        'desc' => 'prop_login.redirecttoprior_desc',
        'type' => 'combo-boolean',
        'options' => '',
        'value' => false,
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'rememberMeKey',
        'desc' => 'prop_login.remembermekey_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => 'rememberme',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'contexts',
        'desc' => 'prop_login.contexts_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
        'lexicon' => 'login:properties',
    ),
    array(
        'name' => 'toPlaceholder',
        'desc' => 'prop_login.toplaceholder_desc',
        'type' => 'textfield',
        'options' => '',
        'value' => '',
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