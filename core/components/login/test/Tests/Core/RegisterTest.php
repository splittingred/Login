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
 * @subpackage test
 */
/**
 * Tests related to Register snippet
 *
 * @package login
 * @subpackage test
 * @group Core
 * @group Register
 */
class RegisterTest extends LoginTestCase {
    /** @var LoginRegisterController */
    public $controller;
    /** @var modUser $user */
    public $user;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->login->loadController('Register');
        $this->user = $this->modx->newObject('modUser');
        $this->user->fromArray(array(
            'id' => 12345678,
            'username' => 'unit.test.user',
            'password' => md5('a test password'),
            'cachepwd' => '',
            'class_key' => 'modUser',
            'active' => false,
            'hash_class' => 'hashing.modMD5',
            'salt' => '',
            'primary_group' => 1,
        ));
        $_POST = array(
            'username' => 'unit.test.user',
            'password' => 'a test password',
            'password_confirm' => 'a test password',
            'email' => LoginTestHarness::$properties['email'],
            'nospam' => '',
            'submitVar' => 'unit-test-register-btn',
        );

        $this->controller->setProperties(array(
            'activation' => true,
            'activationResourceId' => 1,
            'activationEmailSubject' => 'Login Unit Test Activation Email',
            'moderatedResourceId' => 1,
            'preHooks' => '',
            'postHooks' => '',
            'submitVar' => 'unit-test-register-btn',
            'submittedResourceId' => 1,
            'usergroups' => '',
            'validate' => 'nospam:blank',
        ));
        $this->controller->loadDictionary();
    }

    public function tearDown() {
        parent::tearDown();
        $this->user = null;
    }

    /**
     * @param boolean $shouldPass
     * @param string $validate
     * @dataProvider providerValidateFields
     */
    public function testValidateFields($shouldPass,$validate) {
        $this->controller->setProperty('validate',$validate);
        $this->controller->validateFields();
        $this->assertEquals($shouldPass,!$this->controller->validator->hasErrors());
    }
    /**
     * @return array
     */
    public function providerValidateFields() {
        return array(
            array(true,''),
            array(true,'username:required'),
            array(false,'notafield:required'),
            array(true,'password_confirm:password_confirm=^password^'),
            array(false,'password_confirm:password_confirm=^password2^'),
            array(true,'email:email:required'),
            array(true,'email2:email'),
            array(false,'email2:email:required'),
        );
    }

    /**
     * @param boolean $shouldPass
     * @param string $password
     * @dataProvider providerValidatePassword
     */
    public function testValidatePassword($shouldPass,$password) {
        $this->controller->dictionary->set('password',$password);
        $this->controller->setProperty('validate','');
        $this->controller->validateFields();
        $this->controller->validatePassword();
        $this->assertEquals($shouldPass,!$this->controller->validator->hasErrors());
    }
    public function providerValidatePassword() {
        return array(
            array(true,'testing1234'),
            array(false,''),
        );
    }
}