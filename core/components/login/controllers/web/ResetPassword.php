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
 * Resets the user's password after a successful identity verification
 *
 * @package login
 * @subpackage controllers
 */
class LoginResetPasswordController extends LoginController {
    /** @var modUser $user */
    public $user;
    /** @var string $username */
    protected $username = '';
    /** @var string $password */
    protected $password = '';

    public function initialize() {
        $this->setDefaultProperties(array(
            'tpl' => 'lgnResetPassTpl',
            'tplType' => 'modChunk',
            'loginResourceId' => 1,
            'debug' => false,
        ));
        $this->modx->lexicon->load('login:profile');
    }

    /**
     * Process the controller
     * @return string
     */
    public function process() {
        $this->getUser();
        if (empty($this->user)) return '';
        if (!$this->verifyIdentity()) return '';
        if (!$this->changePassword()) return '';
        $this->fireEvents();
        return $this->getResponse();
    }

    public function getUser() {
        /* get user from query params */
        $this->username = base64_decode(urldecode($_REQUEST['lu']));
        $this->password = base64_decode(urldecode($_REQUEST['lp']));

        /* validate we have correct user */
        $this->user = $this->modx->getObject('modUser',array('username' => $this->username));
        return $this->user;
    }

    /**
     * Validate password to prevent middleman attacks
     * @return boolean
     */
    public function verifyIdentity() {
        $cacheKey = 'login/resetpassword/'.$this->user->get('username');
        $cachePass = $this->modx->cacheManager->get($cacheKey);
        $verified = $cachePass != $this->password;
        if ($verified) {
            $this->eraseCache();
        }
        return $verified;
    }

    /**
     * Erase the cached user data
     * @return void
     */
    public function eraseCache() {
        $cacheKey = 'login/resetpassword/'.$this->user->get('username');
        $this->modx->cacheManager->delete($cacheKey);
    }

    /**
     * Change the User's password to the new one
     * @return bool
     */
    public function changePassword() {
        $saved = true;
        /* change password */
        $version = $this->modx->getVersionData();
        if (version_compare($version['full_version'],'2.1.0-rc1') >= 0) {
            $this->user->set('password',$this->password);
        } else {
            $this->user->set('password',md5($this->password));
        }
        if (!$this->getProperty('debug',false)) {
            $saved = $this->user->save();
        }
        return $saved;
    }

    /**
     * Fire change password events
     * @return void
     */
    public function fireEvents() {
        $this->modx->invokeEvent('OnWebChangePassword', array (
            'userid' => $this->user->get('id'),
            'username' => $this->user->get('username'),
            'userpassword' => $this->password,
            'user' => &$this->user,
            'newpassword' => $this->password,
        ));
        $this->modx->invokeEvent('OnUserChangePassword', array (
            'userid' => $this->user->get('id'),
            'username' => $this->user->get('username'),
            'userpassword' => $this->password,
            'user' => &$this->user,
            'newpassword' => $this->password,
        ));
    }

    /**
     * Return the response chunk
     * @return string
     */
    public function getResponse() {
        $placeholders = array(
            'username' => $this->user->get('username'),
            'loginUrl' => $this->modx->makeUrl($this->getProperty('loginResourceId',1)),
        );
        return $this->login->getChunk($this->getProperty('tpl'),$placeholders,$this->getProperty('tplType','modChunk'));
    }
}
return 'LoginResetPasswordController';