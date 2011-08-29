<?php
/**
 * ForgotPassword
 *
 * Copyright 2010 by Jason Coward <jason@modx.com> and Shaun McCormick
 * <shaun@modx.com>
 *
 * ForgotPassword is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ForgotPassword is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ForgotPassword; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package login
 */
/**
 * MODx ForgotPassword Snippet. Displays a form for users who have forgotten
 * their password and gives them the ability to retrieve it.
 *
 * @package login
 */
require_once $modx->getOption('login.core_path',null,$modx->getOption('core_path').'components/login/').'model/login/login.class.php';
$login = new Login($modx,$scriptProperties);

$controller = $login->loadController('ForgotPassword');
$output = $controller->run($scriptProperties);
return $output;
