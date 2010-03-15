<?php
/**
 * Register
 *
 * Copyright 2009 by Shaun McCormick <shaun@collabpad.com>
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
 * @author Shaun McCormick <shaun@collabpad.com>
 * @copyright Copyright &copy; 2009
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 */
$model_path = $modx->getOption('core_path').'components/login/model/login/';
$login = $modx->getService('login','Login',$model_path,$scriptProperties);
$modx->lexicon->load('login:register');

/* set default properties */
$properties = array();

$submitVar = $modx->getOption('submitVar',$scriptProperties,'login-register-btn');
if (!empty($_POST) && (empty($submitVar) || !empty($_POST[$submitVar]))) {
    /* handle validation */
    $login->loadValidator();
    $fields = $login->validator->validateFields($_POST);

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