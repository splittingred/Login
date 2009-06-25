<?php
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