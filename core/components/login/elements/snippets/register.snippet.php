<?php
/**
 * Register
 *
 * Copyright 2010 by Shaun McCormick <shaun@modxcms.com>
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
 * MODx Register Snippet.
 *
 * Handles User registrations.
 *
 * @author Shaun McCormick <shaun@modxcms.com>
 * @copyright Copyright &copy; 2010
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 */
$corePath = $modx->getOption('login.core_path',$config,$modx->getOption('core_path',null,MODX_CORE_PATH).'components/login/');
$login = $modx->getService('login','Login',$corePath.'model/login/',$scriptProperties);
if (!is_object($login) || !($login instanceof Login)) return '';

$modx->lexicon->load('login:register');

/* set default properties */
$properties = array();

$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-register-btn');
if (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]))) {
    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST);

    if (empty($fields['username'])) {
        $login->validator->errors['username'] = $modx->lexicon('register.field_required');
    } else {
        /* make sure username isnt taken */
        $alreadyExists = $modx->getObject('modUser',array('username' => $fields['username']));
        if ($alreadyExists) {
            if ($alreadyExists->get('active') == 0) {
                /* if inactive, probably an expired activation account, so
                 * let's remove it and let user re-register
                 */
                $alreadyExists->remove();
            } else {
                $login->validator->errors['username'] = $modx->lexicon('register.username_taken');
            }
        }
    }
    
    if (empty($fields['password'])) {
        $login->validator->errors['password'] = $modx->lexicon('register.field_required');
    }
    if (empty($fields['email'])) {
        $login->validator->errors['email'] = $modx->lexicon('register.field_required');
    }

    if (empty($login->validator->errors)) {
        $result = require_once $login->config['processorsPath'].'register.php';
        if ($result !== true) {
            $modx->toPlaceholder('message',$result,'error');
        }
    }
    $modx->toPlaceholders($login->validator->errors,'error');
    $modx->toPlaceholders($fields);
}

return '';