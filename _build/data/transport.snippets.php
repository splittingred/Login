<?php
/**
 * @package login
 * @subpackage build
 */

function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php','',$o);
    $o = str_replace('?>','',$o);
    $o = trim($o);
    return $o;
}
$snippets = array();

$snippets[0]= $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'Login',
    'description' => 'Displays a login and logout form.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/login.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.login.php';
$snippets[0]->setProperties($properties);
unset($properties);


$snippets[1]= $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => 1,
    'name' => 'ForgotPassword',
    'description' => 'Displays a forgot password form.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/forgotpassword.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.forgotpassword.php';
$snippets[1]->setProperties($properties);
unset($properties);


$snippets[2]= $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => 2,
    'name' => 'ResetPassword',
    'description' => 'Resets a password from a confirmation email.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/resetpassword.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.resetpassword.php';
$snippets[2]->setProperties($properties);
unset($properties);

$snippets[3]= $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => 3,
    'name' => 'Register',
    'description' => 'Handles forms for registering users on the front-end.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/register.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.register.php';
$snippets[3]->setProperties($properties);
unset($properties);

$snippets[4]= $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => 4,
    'name' => 'ConfirmRegister',
    'description' => 'Handles activation of registered user.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/confirmregister.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.confirmregister.php';
$snippets[4]->setProperties($properties);
unset($properties);

$snippets[5]= $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => 5,
    'name' => 'UpdateProfile',
    'description' => 'Allows front-end updating of a users own profile.',
    'snippet' => getSnippetContent($sources['source_core'].'/elements/snippets/updateprofile.snippet.php'),
),'',true,true);
$properties = include $sources['data'].'properties/properties.updateprofile.php';
$snippets[5]->setProperties($properties);
unset($properties);


return $snippets;