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
 * MODx Login Snippet
 *
 * This snippet handles login POSTs, sending the user back to where they came from or to a specific
 * location if specified in the POST.
 *
 * @version 2.0.0
 * @author Jason Coward <jason@collabpad.com>
 * @author Shaun McCormick <shaun@collabpad.com>
 * @copyright Copyright &copy; 2009
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License
 * version 2 or (at your option) any later version.
 * @package login
 *
 * @property textfield actionKey The REQUEST variable containing the action to take.
 * @property textfield loginKey The actionKey for login.
 * @property textfield logoutKey The actionKey for logout.
 * @property list tplType The type of template to expect for the views:
 *  modChunk - name of chunk to use
 *  file - full path to file to use as tpl
 *  embedded - the tpl is embedded in the page content
 *  inline - the tpl is inline content provided directly
 * @property textfield loginTpl The template for the login view (content based on tplType)
 * @property textfield logoutTpl The template for the logout view (content based on tplType)
 * @property textfield errTpl The template for any errors that occur when processing an view
 * @property list errTplType The type of template to expect for the error messages:
 *  modChunk - name of chunk to use
 *  file - full path to file to use as tpl
 *  inline - the tpl is inline content provided directly
 * @property integer logoutResourceId An explicit resource id to redirect users to on logout
 * @property string loginMsg The string to use for the login action. Defaults to
 * the lexicon string "login".
 * @property string logoutMsg The string to use for the logout action. Defaults
 * to the lexicon string "login.logout"
 */
$output = '';
$modx->lexicon->load('login:default');

$authenticated = $modx->user->isAuthenticated($modx->context->get('key'));

if (isset($_REQUEST[$actionKey]) && !empty($_REQUEST[$actionKey])) {
    if (isset($_REQUEST['login_context']) && !empty($_REQUEST['login_context'])) {
        $loginContext = $_REQUEST['login_context'];
    }
    if (!empty($_POST) && isset($_POST[$actionKey]) && !$authenticated) {
        if ($_POST[$actionKey] == $loginKey) {
            /* set default POST vars if not in form */
            if (!isset($_POST['login_context'])) $_POST['login_context'] = $loginContext;

            /* send to login processor and handle response */
            $response = $modx->executeProcessor(array(
                'action' => 'login',
                'location' => 'security'
            ));
            if (!empty($response) && is_array($response)) {
                if (!empty($response['success']) && isset($response['object'])) {
                    if (!empty($loginResourceId) && ($url = $modx->makeUrl($loginResourceId, $loginContext, '', 'full'))) {
                        $modx->sendRedirect($url);
                    } elseif (isset($response['object']['url'])) {
                        $modx->sendRedirect($response['object']['url']);
                    } else {
                        $modx->sendRedirect($modx->getOption('site_url'));
                    }
                } else {
                    $errorOutput = '';
                    if (isset($response['errors']) && !empty($response['errors'])) {
                        foreach ($response['errors'] as $error) {
                            $errorOutput .= $modx->parseChunk($errTpl, $error);
                        }
                    } elseif (isset($response['message']) && !empty($response['message'])) {
                        $errorOutput = $modx->parseChunk($errTpl, array('msg' => $response['message']));
                    } else {
                        $errorOutput = $modx->parseChunk($errTpl, array('msg' => $modx->lexicon('login.login_err')));
                    }
                    $modx->setPlaceholder('errors', $errorOutput);
                }
            }
        } else {
            $modx->log(MODX_LOG_LEVEL_ERROR,$modx->lexicon('login.invalid_post',array(
                'action' => $_POST[$actionKey],
            )));
        }
    } elseif ($_REQUEST[$actionKey] == $logoutKey && $authenticated) {
        /* set default REQUEST vars if not provided */
        if (empty($_REQUEST['login_context'])) $_REQUEST['login_context'] = $loginContext;

        /* send to logout processor and handle response */
        $response = $modx->executeProcessor(array(
            'action' => 'logout',
            'location' => 'security'
        ));
        if (!empty($response) && is_array($response)) {
            if (!empty($response['success']) && isset($response['object'])) {
                if (isset($response['object']['url'])) {
                    $modx->sendRedirect($response['object']['url']);
                } elseif (!empty($logoutResourceId)) {
                    $modx->sendRedirect($modx->makeUrl($logoutResourceId));
                } else {
                    $modx->sendRedirect($_SERVER['REQUEST_URI']);
                }
            } else {
                $errorOutput = '';
                if (isset($response['errors']) && !empty($response['errors'])) {
                    foreach ($response['errors'] as $error) {
                        $errorOutput .= $modx->parseChunk($errTpl, $error);
                    }
                } elseif (isset($response['message']) && !empty($response['message'])) {
                    $errorOutput = $modx->parseChunk($errTpl, array('msg' => $response['message']));
                } else {
                    $errorOutput = $modx->parseChunk($errTpl, array('msg' => $modx->lexicon('login.logout_err')));
                }
                $modx->setPlaceholder('errors', $errorOutput);
            }
        }
    }
}

$tpl = $authenticated ? $logoutTpl : $loginTpl;
$actionMsg = $authenticated
    ? (!empty($logoutMsg) ? $logoutMsg : $modx->lexicon('login.logout'))
    : (!empty($loginMsg) ? $loginMsg : $modx->lexicon('login'));

$modx->setPlaceholder('actionMsg', $actionMsg);
$phs = $authenticated ? $scriptProperties : array_merge($scriptProperties, $_POST);
/* make sure to strip out logout GET parameter to prevent ghost logout */
$phs['request_uri'] = str_replace(array('?service=logout','&service=logout'),'',$_SERVER['REQUEST_URI']);

switch ($tplType) {
    case 'embedded':
        if (!$authenticated) $modx->setPlaceholders($phs);
        break;
    case 'modChunk':
        $output .= $modx->getChunk($tpl, $phs);
        break;
    case 'file':
        $output .= file_get_contents($tpl);
        $modx->setPlaceholders($phs);
        break;
    case 'inline':
    default:
        /* default is inline, meaning the tpl content was provided directly in the property */
        $output .= $tpl;
        $modx->setPlaceholders($phs);
        break;
}

return $output;