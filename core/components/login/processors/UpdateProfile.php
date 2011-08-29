<?php
/**
 * Login
 *
 * Copyright 2010 by Jason Coward <jason@modx.com> and Shaun McCormick <shaun+login@modx.com>
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
 * Update the user's profile
 *
 * @package login
 * @subpackage processors
 */
class LoginUpdateProfileProcessor extends LoginProcessor {
    /** @var modUserProfile $profile */
    public $profile;
    /** @var boolean $usernameChanged */
    public $usernameChanged = false;
    /** @var string $oldUsername */
    public $oldUsername;

    /**
     * @return boolean|string
     */
    public function process() {
        $this->getProfile();
        if (empty($this->profile)) {
            return $this->modx->lexicon('login.profile_err_nf');
        }

        $this->removeSpamFields();
        $this->setExtended();
        $this->setFields();
        if (!$this->syncUsername()) {
            return $this->modx->lexicon('login.username_err_ae');
        }
        if (!$this->save()) {
            return $this->modx->lexicon('login.profile_err_save');
        }

        $this->runPostHooks();
        $this->handleSuccess();
        return true;
    }

    /**
     * Get the user's profile
     * @return modUserProfile
     */
    public function getProfile() {
        $this->profile = $this->modx->user->getOne('Profile');
        return $this->profile;
    }

    /**
     * Remove any spam/submitVar fields from the field list
     * @return void
     */
    public function removeSpamFields() {
        $this->controller->dictionary->remove('nospam');
        $this->controller->dictionary->remove('blank');
        $submitVar = $this->controller->getProperty('submitVar');
        if (!empty($submitVar)) {
            $this->controller->dictionary->remove($submitVar);
        }
    }

    /**
     * If desired, set any extended fields
     * @return void
     */
    public function setExtended() {
        if ($this->controller->getProperty('useExtended',true,'isset')) {
            $allowedExtendedFields = $this->controller->getProperty('allowedExtendedFields','');
            $allowedExtendedFields = !empty($allowedExtendedFields) ? explode(',',$allowedExtendedFields) : array();
            /* first cut out regular fields */
            $excludeExtended = $this->controller->getProperty('excludeExtended','');
            $excludeExtended = explode(',',$excludeExtended);
            $profileFields = $this->profile->toArray();
            $userFields = $this->modx->user->toArray();
            $newExtended = array();
            $fields = $this->controller->dictionary->toArray();
            foreach ($fields as $field => $value) {
                $isValidExtended = true;
                if (!empty($allowedExtendedFields)) {
                    if (!in_array($field,$allowedExtendedFields)) {
                        $isValidExtended = false;
                    }
                }
                if (isset($profileFields[$field]) || isset($userFields[$field]) || $field == 'password_confirm' || $field == 'passwordconfirm' || in_array($field,$excludeExtended) || $field == 'nospam' || $field == 'nospam:blank') {
                    $isValidExtended = false;
                }

                if ($isValidExtended) {
                    $newExtended[$field] = $value;
                }
            }
            /* now merge with existing extended data */
            $extended = $this->profile->get('extended');
            $extended = is_array($extended) ? array_merge($extended,$newExtended) : $newExtended;
            $this->profile->set('extended',$extended);
        }
    }

    /**
     * Set the form fields to the user
     * @return void
     */
    public function setFields() {
        $allowedFields = $this->controller->getProperty('allowedFields','');
        $allowedFields = !empty($allowedFields) ? explode(',',$allowedFields) : array();
        $fields = $this->controller->dictionary->toArray();
        foreach ($fields as $key => $value) {
            $isValidField = true;
            if (!empty($allowedFields)) {
                if (!in_array($key,$allowedFields)) {
                    $isValidField = false;
                }
            }
            if ($isValidField) {
                $this->profile->set($key,$value);
            }
        }
    }

    /**
     * Allow changing of username for user via syncUsername property
     * @return boolean
     */
    public function syncUsername() {
        $synced = true;
        $syncUsername = $this->controller->getProperty('syncUsername',false,'isset');
        $this->oldUsername = $this->modx->user->get('username');
        if (!empty($syncUsername)) {
            $newUsername = $this->profile->get($syncUsername);
            if (!empty($newUsername) && strcmp($newUsername,$this->oldUsername) != 0) {
                $alreadyExists = $this->modx->getCount('modUser',array('username' => $newUsername));
                if (!empty($alreadyExists)) {
                    $synced = false;
                } else {
                    $this->modx->user->set('username',$newUsername);
                    $this->usernameChanged = true;
                    $synced = $this->modx->user->save();
                }
            }
        }
        return $synced;
    }

    /**
     * Save the user data
     * @return boolean
     */
    public function save() {
        $this->modx->user->addOne($profile,'Profile');
        $saved = $this->modx->user->save();
        if (!$saved) {
            /* revert username change */
            if ($this->usernameChanged) {
                $this->modx->user->set('username',$this->oldUsername);
                $this->modx->user->save();
            }
        }
        return $saved;
    }

    /**
     * Run any post-update hooks
     * @return void
     */
    public function runPostHooks() {
        $postHooks = $this->controller->getProperty('postHooks','');
        $this->controller->loadHooks('postHooks');
        $fields['updateprofile.user'] = &$this->modx->user;
        $fields['updateprofile.profile'] =& $this->profile;
        $fields['updateprofile.usernameChanged'] = $this->usernameChanged;
        $this->controller->postHooks->loadMultiple($postHooks,$this->dictionary->toArray());

        /* process hooks */
        if ($this->controller->postHooks->hasErrors()) {
            $errors = array();
            $errTpl = $this->controller->getProperty('errTpl');
            $errs = $this->controller->postHooks->getErrors();
            foreach ($errs as $key => $error) {
                $errors[$key] = str_replace('[[+error]]',$error,$errTpl);
            }
            $this->modx->toPlaceholders($errors,'error');

            $errorMsg = $this->controller->postHooks->getErrorMessage();
            $this->modx->toPlaceholder('message',$errorMsg,'error');
        }
    }

    /**
     * Set the success placeholder
     * @return void
     */
    public function handleSuccess() {
        $successMsg = $this->controller->getProperty('successMsg',$this->modx->lexicon('login.profile_updated'));
        $this->modx->toPlaceholder($this->controller->getProperty('successMsgPlaceholder','error.message'),$successMsg);
    }
}
return 'LoginUpdateProfileProcessor';
