<?php
/**
 * Login
 *
 * Copyright 2010 by Shaun McCormick <shaun+login@modx.com>
 *
 * Login is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
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
 * Handles logging in and out of users
 *
 * @package login
 * @subpackage controllers
 */
class LoginLoginController extends LoginController {
    public $isAuthenticated = false;
    /** @var LoginDictionary $dictionary */
    public $dictionary;

    public function initialize() {
        $this->setDefaultProperties(array(
            'loginTpl' => 'lgnLoginTpl',
            'logoutTpl' => 'lgnLogoutTpl',
            'loginMsg' => '',
            'logoutMsg' => '',
            'preHooks' => '',
            'tplType' => 'modChunk',
            'actionKey' => 'service',
            'loginKey' => 'login',
            'logoutKey' => 'logout',
            'errorPrefix' => 'error',
            'errTpl' => 'lgnErrTpl',
            'errTplType' => 'modChunk',
            'rememberMeKey' => 'rememberme',
            'loginContext' => $this->modx->context->get('key'),
            'contexts' => '',
        ));

        if (!empty($_REQUEST['login_context'])) {
            $this->setProperty('loginContext',$_REQUEST['login_context']);
        }
        if (!empty($_REQUEST['add_contexts'])) {
            $this->setProperty('contexts',$_REQUEST['add_contexts']);
        }
        $this->isAuthenticated = $this->modx->user->isAuthenticated($this->getProperty('loginContext'));
    }

    /**
     * Process the controller
     * @return string
     */
    public function process() {
        if (!empty($_REQUEST[$this->getProperty('actionKey','service')])) {
            $this->handleRequest();
        }
        $content = $this->renderForm();
        return $this->output($content);
    }

    /**
     * Render the logout or login form
     * @return string
     */
    public function renderForm() {
        $redirectToPrior = $this->getProperty('redirectToPrior',false,'isset');
        $tpl = $this->isAuthenticated ? $this->getProperty('logoutTpl') : $this->getProperty('loginTpl');
        $actionMsg = $this->isAuthenticated
            ? $this->getProperty('logoutMsg',$this->modx->lexicon('login.logout'))
            : $this->getProperty('loginMsg',$this->modx->lexicon('login'));

        $this->modx->setPlaceholder('actionMsg', $actionMsg);
        $phs = $this->isAuthenticated ? $this->getProperties() : array_merge($this->getProperties(), $_POST);
        foreach ($phs as $k => $v) {
            $phs[$k] = str_replace(array('[',']'),array('&#91;','&#93;'),$v);
        }
        /* make sure to strip out logout GET parameter to prevent ghost logout */
        $logoutKey = $this->getProperty('logoutKey','logout');
        if (!$redirectToPrior) {
            $phs['request_uri'] = str_replace(array('?service='.$logoutKey,'&service='.$logoutKey,'&amp;service='.$logoutKey),'',$_SERVER['REQUEST_URI']);
        } else {
            $phs['request_uri'] = str_replace(array('?service='.$logoutKey,'&service='.$logoutKey,'&amp;service='.$logoutKey),'',$_SERVER['HTTP_REFERER']);
        }

        /* properly build logout url */
        if ($this->isAuthenticated) {
            $phs['logoutUrl'] = $phs['request_uri'];
            $phs['logoutUrl'] .= strpos($phs['logoutUrl'],'?') ? ($this->modx->getOption('xhtml_urls',null,false) ? '&amp;' : '&') : '?';
            $phs['logoutUrl'] .= $phs['actionKey'].'='.$phs['logoutKey'];
            $phs['logoutUrl'] = str_replace(array('?=','&='),'',$phs['logoutUrl']);
        }

        $this->loadReCaptcha();

        return $this->login->getChunk($tpl,$phs,$this->getProperty('tplType','modChunk'));
    }

    /**
     * Either output the content or set it as a placeholder
     * @param string $content
     * @return string
     */
    public function output($content = '') {
        $toPlaceholder = $this->getProperty('toPlaceholder','');
        if (!empty($toPlaceholder)) {
            $this->modx->setPlaceholder($toPlaceholder,$content);
            return '';
        }
        return $content;
    }

    /**
     * Check for and load reCaptcha
     * @return boolean
     */
    public function loadReCaptcha() {
        $loaded = false;
        $preHooks = $this->getProperty('preHooks','');
        /* if using recaptcha, load recaptcha html */
        if (strpos($preHooks,'recaptcha') !== false && !$this->isAuthenticated) {
            /** @var reCaptcha $reCaptcha */
            $reCaptcha = $this->modx->getService('recaptcha','reCaptcha',$this->login->config['modelPath'].'recaptcha/');
            if ($reCaptcha instanceof reCaptcha) {
                $this->modx->lexicon->load('login:recaptcha');
                $recaptchaTheme = $this->getProperty('recaptchaTheme','clean');
                $recaptchaWidth = $this->getProperty('recaptchaWidth',500);
                $recaptchaHeight = $this->getProperty('recaptchaHeight',300);
                $html = $reCaptcha->getHtml($recaptchaTheme,$recaptchaWidth,$recaptchaHeight);
                $this->modx->setPlaceholder('login.recaptcha_html',$html);
                $loaded = true;
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] '.$this->modx->lexicon('login.recaptcha_err_load'));
            }
        }
        return $loaded;
    }

    /**
     * Handle any POST request
     *
     * @return void
     */
    public function handleRequest() {
        $this->loadDictionary();
        $actionKey = $this->getProperty('actionKey','service');
        
        if (!empty($_POST) && isset($_POST[$actionKey]) && !$this->isAuthenticated) {
            if ($_POST[$actionKey] == $this->getProperty('loginKey','login')) {
                $this->login();
            } else {
                $this->modx->log(modX::LOG_LEVEL_ERROR,$this->modx->lexicon('login.invalid_post',array(
                    'action' => $_POST[$actionKey],
                )));
            }
        } elseif ($_REQUEST[$actionKey] == $this->getProperty('logoutKey','logout') && $this->isAuthenticated) {
            $this->logout();
        }
    }

    /**
     * Handle a Login submission
     * 
     * @return void
     */
    public function login() {
        /* set default POST vars if not in form */
        if (empty($_POST['login_context'])) $_POST['login_context'] = $this->getProperty('loginContext');

        if ($this->runPreLoginHooks()) {
            $response = $this->runLoginProcessor();
            
            /* if we've got a good response, proceed */
            if (!empty($response) && !$response->isError()) {
                $this->runPostLoginHooks($response);

                /* process posthooks for login */
                if ($this->postHooks->hasErrors()) {
                    $errorPrefix = $this->getProperty('errorPrefix','error');
                    $this->modx->toPlaceholders($this->postHooks->getErrors(),$errorPrefix);

                    $errorMsg = $this->postHooks->getErrorMessage();
                    $this->modx->toPlaceholder('message',$errorMsg,$errorPrefix);
                } else {
                    $this->redirectAfterLogin($response);
                }

            /* logout failed, output error */
            } else {
                $this->checkForRedirectOnFailedAuth($response);
                $errorOutput = $this->prepareFailureMessage($response,$this->modx->lexicon('login.login_err'));
                $this->modx->setPlaceholder('errors', $errorOutput);
            }
        }
    }

    /**
     * Run any preHooks before logging in
     * 
     * @return boolean
     */
    public function runPreLoginHooks() {
        $success = true;
        
        /* do pre-login hooks */
        $this->loadHooks('preHooks');
        $this->preHooks->loadMultiple($this->getProperty('preHooks',''),$this->dictionary->toArray(),array(
            'mode' => Login::MODE_LOGIN,
        ));

        /* process prehooks */
        if ($this->preHooks->hasErrors()) {
            $this->modx->toPlaceholders($this->preHooks->getErrors(),$this->getProperty('errorPrefix','error'));

            $errorMsg = $this->preHooks->getErrorMessage();
            $errorOutput = $this->modx->parseChunk($this->getProperty('errTpl'), array('msg' => $errorMsg));
            $this->modx->setPlaceholder('errors',$errorOutput);
            $success = false;
        }
        return $success;
    }

    /**
     * @return modProcessorResponse
     */
    public function runLoginProcessor() {
        $fields = $this->dictionary->toArray();
        /* send to login processor and handle response */
        $c = array(
            'login_context' => $this->getProperty('loginContext'),
            'add_contexts' => $this->getProperty('contexts',''),
            'username' => $fields['username'],
            'password' => $fields['password'],
            'returnUrl' => $fields['returnUrl'],
            'rememberme' => !empty($fields[$this->getProperty('rememberMeKey','rememberme')]) ? true : false,
        );
        return $this->modx->runProcessor('security/login',$c);
    }

    /**
     * @param modProcessorResponse $response
     * @param string $defaultErrorMessage
     * @return string
     */
    public function prepareFailureMessage(modProcessorResponse $response,$defaultErrorMessage = '') {
        $errorOutput = '';
        $errTpl = $this->getProperty('errTpl');
        $errors = $response->getFieldErrors();
        $message = $response->getMessage();
        if (!empty($errors)) {
            foreach ($errors as $error) {
                $errorOutput .= $this->modx->parseChunk($errTpl, $error);
            }
        } elseif (!empty($message)) {
            $errorOutput = $this->modx->parseChunk($errTpl, array('msg' => $message));
        } else {
            $errorOutput = $this->modx->parseChunk($errTpl, array('msg' => $defaultErrorMessage));
        }
        return $errorOutput;
    }

    /**
     * Check to see if the user wants to redirect to a separ
     * @param modProcessorResponse $response
     * @return void
     */
    public function checkForRedirectOnFailedAuth(modProcessorResponse $response) {
        $redirectToOnFailedAuth = $this->getProperty('redirectToOnFailedAuth',false,'isset');
        if ($redirectToOnFailedAuth && $redirectToOnFailedAuth != $this->modx->resource->get('id')) {
            $p = array(
                'u' => $this->dictionary->get('username'),
            );
            $message = $response->getMessage();
            if (!empty($message)) $params['m'] = $message;
            $url = $this->modx->makeUrl($redirectToOnFailedAuth,'',$p,'full');
            $this->modx->sendRedirect($url);
        }
    }

    /**
     * Run any postHooks specified after the user has logged in
     * 
     * @param modProcessorResponse $response
     * @return void
     */
    public function runPostLoginHooks(modProcessorResponse $response) {
        $responseArray = $response->getObject();

        /* do post hooks */
        $postHooks = $this->getProperty('postHooks','');
        $this->loadHooks('postHooks');
        $fields = $_POST;
        $fields['response'] =& $responseArray;
        $fields['contexts'] =& $contexts;
        $fields['loginContext'] =& $loginContext;
        $fields['loginResourceId'] =& $loginResourceId;
        $this->postHooks->loadMultiple($postHooks,$fields,array(
            'mode' => Login::MODE_LOGIN,
        ));

    }

    /**
     * Redirect the user after logging them in
     * 
     * @param modProcessorResponse $response
     * @return void
     */
    public function redirectAfterLogin(modProcessorResponse $response) {
        $responseArray = $response->getObject();
        /* allow dynamic redirection handling */
        $redirectBack = $this->modx->getOption('redirectBack',$_REQUEST,$this->getProperty('redirectBack',''));
        $redirectBackParams = $this->modx->getOption('redirectBackParams',$_REQUEST,$this->getProperty('redirectBackParams',''));
        if (!empty($redirectBackParams)) {
            $redirectBackParams = $this->login->decodeParams($redirectBackParams);
        }
        /* otherwise specify a specific resource to redirect to */
        $loginResourceId = $this->getProperty('loginResourceId',$redirectBack);
        /* login posthooks succeeded, now redirect */

        if (!empty($loginResourceId)) {
            $loginResourceParams = $this->getProperty('loginResourceParams',$redirectBackParams);
            if (!empty($loginResourceParams) && !is_array($loginResourceParams)) {
                $loginResourceParams = $this->modx->fromJSON($loginResourceParams);
            }
            $url = $this->modx->makeUrl($loginResourceId,'',$loginResourceParams,'full');
            $this->modx->sendRedirect($url);
        } elseif (!empty($responseArray) && !empty($responseArray['url'])) {
            $this->modx->sendRedirect($responseArray['url']);
        } else {
            $this->modx->sendRedirect($this->modx->getOption('site_url'));
        }
    }

    public function logout() {
        /* set default REQUEST vars if not provided */
        if (empty($_REQUEST['login_context'])) $_REQUEST['login_context'] = $this->getProperty('loginContext');

        if ($this->runPreLogoutHooks()) {
            /* send to logout processor and handle response for each context */
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('security/logout',array(
                'login_context' => $this->getProperty('loginContext',$this->modx->context->get('key')),
                'add_contexts' => $this->getProperty('contexts',''),
            ));

            /* if successful logout */
            if (!empty($response) && !$response->isError()) {
                $this->runPostLogoutHooks($response);
                $this->redirectAfterLogout($response);

            /* logout failed, output error */
            } else {
                $errorOutput = $this->prepareFailureMessage($response,$this->modx->lexicon('login.logout_err'));
                $this->modx->setPlaceholder('errors', $errorOutput);
            }
        }
    }

    /**
     * @return boolean
     */
    public function runPreLogoutHooks() {
        $success = true;
        $this->loadHooks('preHooks');
        $this->preHooks->loadMultiple($this->getProperty('preHooks',''),$this->dictionary->toArray(),array(
            'mode' => Login::MODE_LOGOUT,
        ));

        if ($this->preHooks->hasErrors()) {
            $this->modx->toPlaceholders($this->preHooks->getErrors(),$this->getProperty('errorPrefix','error'));

            $errorMsg = $this->preHooks->getErrorMessage();
            $errorOutput = $this->modx->parseChunk($this->getProperty('errTpl'), array('msg' => $errorMsg));
            $this->modx->setPlaceholder('errors',$errorOutput);
            $success = false;
        }
        return $success;
    }

    /**
     * Run any post-logout hooks
     *
     * @param modProcessorResponse $response
     * @return boolean
     */
    public function runPostLogoutHooks(modProcessorResponse $response) {
        $success = true;
        
        /* do post hooks for logout */
        $postHooks = $this->getProperty('postHooks','');
        $this->loadHooks('postHooks');
        $fields = $this->dictionary->toArray();
        $fields['response'] =& $response->getObject();
        $fields['contexts'] =& $contexts;
        $fields['loginContext'] =& $loginContext;
        $fields['logoutResourceId'] =& $logoutResourceId;
        $this->postHooks->loadMultiple($postHooks,$fields,array(
            'mode' => Login::MODE_LOGOUT,
        ));

        /* log posthooks errors */
        if ($this->postHooks->hasErrors()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] Post-Hook errors: '.print_r($this->postHooks->getErrors(),true));

            $errorMsg = $this->postHooks->getErrorMessage();
            if (!empty($errorMsg)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'[Login] Post-Hook error: '.$errorMsg);
            }
            $success = false;
        }
        return $success;
    }

    /**
     * Redirect the user after they logout
     * 
     * @param modProcessorResponse $response
     * @return void
     */
    public function redirectAfterLogout(modProcessorResponse $response) {
        $responseArray = $response->getObject();

        /* redirect */
        $logoutResourceId = $this->getProperty('logoutResourceId',0);
        if (!empty($responseArray) && !empty($responseArray['url'])) {
            $this->modx->sendRedirect($responseArray['url']);
        } elseif (!empty($logoutResourceId)) {
            $logoutResourceParams = $this->getProperty('logoutResourceParams','');
            if (!empty($logoutResourceParams)) {
                $logoutResourceParams = $this->modx->fromJSON($logoutResourceParams);
            }
            $url = $this->modx->makeUrl($logoutResourceId,'',$logoutResourceParams,'full');
            $this->modx->sendRedirect($url);
        } else {
            $this->modx->sendRedirect($_SERVER['REQUEST_URI']);
        }
    }
}
return 'LoginLoginController';