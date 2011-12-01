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
 * Displays the profile of a specific user
 *
 * @package login
 * @subpackage controllers
 */
class LoginProfileController extends LoginController {
    /** @var modUser $user */
    public $user;
    /** @var modUserProfile $profile */
    public $profile;

    public function initialize() {
        $this->setDefaultProperties(array(
            'prefix' => '',
            'user' => false,
        ));
        $this->modx->lexicon->load('login:profile');
    }

    /**
     * Process the controller
     * @return string
     */
    public function process() {
        if (!$this->getUser()) {
            return '';
        }
        if (!$this->getProfile()) {
            return '';
        }

        $this->setToPlaceholders();
        return '';
    }

    /**
     * Set the user data to placeholders
     * 
     * @return array
     */
    public function setToPlaceholders() {
        $placeholders = array_merge($this->profile->toArray(),$this->user->toArray());
        $extended = $this->getExtended();
        $placeholders = array_merge($extended,$placeholders);
        $placeholders = $this->removePasswordPlaceholders($placeholders);
        $this->modx->toPlaceholders($placeholders,$this->getProperty('prefix','','isset'),'');
        return $placeholders;
    }

    /**
     * Remove the password fields from the outputted placeholders
     * @param array $placeholders
     * @return array
     */
    public function removePasswordPlaceholders(array $placeholders = array()) {
        unset($placeholders['password'],$placeholders['cachepwd']);
        return $placeholders;
    }

    /**
     * Get extended fields for a user
     * @return array
     */
    public function getExtended() {
        $extended = array();
        if ($this->getProperty('useExtended',true,'isset')) {
            $extended = $this->profile->get('extended');
        }
        return (array) $extended;
    }

    /**
     * Get the profile for the user
     * 
     * @return bool|modUserProfile
     */
    public function getProfile() {
        $this->profile = $this->user->getOne('Profile');
        if (empty($this->profile)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find profile for user: '.$this->user->get('username'));
            return false;
        }
        return $this->profile;
    }

    /**
     * Get the specified or active user
     * @return boolean|modUser
     */
    public function getUser() {
        $user = $this->getProperty('user',false,'isset');

        /* verify authenticated status if no user specified */
        if (empty($user) && !$this->modx->user->hasSessionContext($this->modx->context->get('key'))) {
            $this->user = false;
        }
        /* specifying a specific user, so try and get it */
        if (!empty($user)) {
            $username = $user;
            $userNum = (int)$user;
            $c = array();
            if (!empty($userNum)) {
                $c['id'] = $userNum;
            } else {
                $c['username'] = $username;
            }
            $this->user = $this->modx->getObject('modUser',$c);
            if (!$this->user) {
                $this->modx->log(modX::LOG_LEVEL_ERROR,'Could not find user: '.$username);
                $this->user = false;
            }
        /* just use current user if user is logged in */
        } else {
            if (!$this->modx->user->hasSessionContext($this->modx->context->get('key'))) {
                $this->user = false;
            } else {
                $this->user =& $this->modx->user;
            }
        }
        return $this->user;
    }
}
return 'LoginProfileController';