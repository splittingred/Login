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
 * Add chunks to package
 *
 * @package login
 * @subpackage build
 */
$chunks = array();
$chunks[1]= $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => 1,
    'name' => 'lgnLoginTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnlogintpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'lgnLogoutTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnlogouttpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 3,
    'name' => 'lgnErrTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnerrtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[4]= $modx->newObject('modChunk');
$chunks[4]->fromArray(array(
    'id' => 4,
    'name' => 'lgnForgotPassEmail',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnforgotpassemail.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[5]= $modx->newObject('modChunk');
$chunks[5]->fromArray(array(
    'id' => 5,
    'name' => 'lgnForgotPassSentTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnforgotpasssenttpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[6]= $modx->newObject('modChunk');
$chunks[6]->fromArray(array(
    'id' => 6,
    'name' => 'lgnForgotPassTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnforgotpasstpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[7]= $modx->newObject('modChunk');
$chunks[7]->fromArray(array(
    'id' => 7,
    'name' => 'lgnResetPassTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnresetpasstpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[8]= $modx->newObject('modChunk');
$chunks[8]->fromArray(array(
    'id' => 8,
    'name' => 'lgnRegisterForm',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnregisterformtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[9]= $modx->newObject('modChunk');
$chunks[9]->fromArray(array(
    'id' => 9,
    'name' => 'lgnActivateEmailTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnactivateemailtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[10]= $modx->newObject('modChunk');
$chunks[10]->fromArray(array(
    'id' => 10,
    'name' => 'lgnActiveUser',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/elements/chunks/lgnactiveuser.chunk.tpl'),
    'properties' => '',
),'',true,true);

return $chunks;