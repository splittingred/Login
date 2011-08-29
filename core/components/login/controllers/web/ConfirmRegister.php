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
 * Confirms a User's Registration after activation
 *
 * @package login
 * @subpackage controllers
 */
class LoginConfirmRegisterController extends LoginController {
    /** @var string $username */
    public $username;
    /** @var string $password */
    public $password;
    /** @var modUser $user */
    public $user;

    public function initialize() {
        $this->setDefaultProperties(array(
            'authenticate' => true,
            'authenticateContexts' => $this->modx->context->get('key'),
            'errorPage' => false,
            'redirectTo' => false,
            'redirectParams' => '',
            'redirectBack' => false,
            'redirectBackParams' => '',
        ));
    }

    public function process() {
        $this->verifyManifest();
        $this->getUser();
        $this->validatePassword();

        $this->onBeforeUserActivate();

        /* activate user */
        $this->user->set('active',1);
        $this->user->set('cachepwd','');
        if (!$this->user->save()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Register] Could not save activated user: '.$user->get('username'));
            return '';
        }
        
        /* invoke OnUserActivate event */
        $this->modx->invokeEvent('OnUserActivate',array(
            'user' => &$user,
        ));

        $this->addSessionContexts();

        $this->redirectBack();
        return '';
    }

    /**
     * Verify that the username/password hashes were correctly sent to prevent middle-man attacks
     * @return boolean
     */
    public function verifyManifest() {
        $verified = false;
        if (empty($_REQUEST['lp']) || empty($_REQUEST['lu'])) {
            $this->redirectAfterFailure();
        } else {
            /* get user from query params */
            $this->username = base64_decode(urldecode(rawurldecode($_REQUEST['lu'])));
            $this->password = base64_decode(urldecode(rawurldecode($_REQUEST['lp'])));
            $verified = true;
        }
        return $verified;
    }

    /**
     * Validate we have correct user
     * @return modUser
     */
    public function getUser() {
        $this->user = $this->modx->getObject('modUser',array('username' => $this->username));
        if ($this->user == null || $this->user->get('active')) {
            $this->redirectAfterFailure();
        }
        return $this->user;
    }

    /**
     * Handle the redirection after a failed verification
     * @return void
     */
    public function redirectAfterFailure() {
        $errorPage = $this->getProperty('errorPage',false,'isset');
        if (!empty($errorPage)) {
            $url = $this->modx->makeUrl($errorPage,'','','full');
            $this->modx->sendRedirect($url);
        } else {
            $this->modx->sendErrorPage();
        }
    }

    /**
     * Validate password to prevent middleman attacks
     * @return boolean
     */
    public function validatePassword() {
        $this->modx->getService('registry', 'registry.modRegistry');
        $this->modx->registry->addRegister('login','registry.modFileRegister');
        $this->modx->registry->login->connect();
        $this->modx->registry->login->subscribe('/useractivation/'.$this->user->get('username'));
        $msgs = $this->modx->registry->login->read();
        if (empty($msgs)) $this->modx->sendErrorPage();
        $found = false;
        foreach ($msgs as $msg) {
            if ($msg == $this->password) {
                $found = true;
            }
        }
        if (!$found) {
            $this->redirectAfterFailure();
        }
        return $found;
    }

    /**
     * Invoke OnBeforeUserActivateEvent, if result returns anything, do not proceed
     * @return boolean
     */
    public function onBeforeUserActivate() {
        $success = true;
        $result = $this->modx->invokeEvent('OnBeforeUserActivate',array(
            'user' => &$this->user,
        ));
        $preventActivation = $this->login->getEventResult($result);
        if (!empty($preventActivation)) {
            $success = false;
            $this->modx->log(modX::LOG_LEVEL_ERROR,'[Register] OnBeforeUserActivate event prevented activation for "'.$this->user->get('username').'" by returning false: '.$preventActivation);
            $this->redirectAfterFailure();
        }
        return $success;
    }

    /**
     * Login the user to the specified contexts
     * @return void
     */
    public function addSessionContexts() {
        if ($this->getProperty('authenticate',true)) {
            $this->modx->user =& $user;
            $this->modx->getUser();
            $contexts = $this->getProperty('authenticateContexts',$this->modx->context->get('key'));
            $contexts = explode(',',$contexts);
            foreach ($contexts as $ctx) {
                $this->modx->user->addSessionContext($ctx);
            }
        }
    }


    /**
     * If wanting to redirect after confirmed registration (for shopping carts)
     * Also allow &redirectBack parameter sent in confirmation email to redirect
     * to a form requiring registration
     */
    public function redirectBack() {
        $redirectBack = $this->modx->getOption('redirectBack',$_REQUEST,$this->getProperty('redirectBack',false,'isset'));
        $redirectBackParams = $this->modx->getOption('redirectBackParams',$_REQUEST,$this->getProperty('redirectBackParams',''));
        if (!empty($redirectBackParams)) {
            $redirectBackParams = $this->login->decodeParams($redirectBackParams);
        }
        $redirectTo = $this->getProperty('redirectTo',$redirectBack);
        if (!empty($redirectTo)) {
            /* allow custom redirection params */
            $redirectParams = $this->getProperty('redirectParams',$redirectBackParams);
            if (!empty($redirectParams) && !is_array($redirectParams)) $redirectParams = $this->modx->fromJSON($redirectParams);
            if (empty($redirectParams) || !is_array($redirectParams)) $redirectParams = array();

            /* handle persist params from Register snippet */
            $persistParams = $_GET;
            unset($persistParams['lp'],$persistParams['lu']);
            $persistParams['username'] = $this->user->get('username');
            $persistParams['userid'] = $this->user->get('id');
            $redirectParams = array_merge($redirectParams,$persistParams);
            unset($redirectParams[$this->modx->getOption('request_param_alias',null,'q')],$redirectParams['redirectBack']);

            /* redirect user */
            $url = $this->modx->makeUrl($redirectTo,'',$redirectParams,'full');
            $this->modx->sendRedirect($url);
        }
    }
}
return 'LoginConfirmRegisterController';