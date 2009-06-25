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
    'snippet' => file_get_contents($sources['source_core'].'/chunks/lgnlogintpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[2]= $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => 2,
    'name' => 'lgnLogoutTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/chunks/lgnlogouttpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

$chunks[3]= $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => 3,
    'name' => 'lgnErrTpl',
    'description' => '',
    'snippet' => file_get_contents($sources['source_core'].'/chunks/lgnerrtpl.chunk.tpl'),
    'properties' => '',
),'',true,true);

return $chunks;