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
 * @group Processors
 * @group Processors.Register
 * @group Register
 */
class RegisterProcessorTest extends LoginTestCase {
    /** @var LoginRegisterController $controller */
    public $controller;
    /** @var LoginRegisterProcessor $processor */
    public $processor;
    /** @var modUser $user */
    public $user;
    /** @var modUserProfile $profile */
    public $profile;
    
    public function setUp() {
        parent::setUp();

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
            'email' => LoginTestHarness::$properties['email'],
        ));
        $this->profile = $this->modx->newObject('modUserProfile');
        $this->profile->fromArray(array(
            'internalKey' => 12345678,
            'email' => LoginTestHarness::$properties['email'],
            'blocked' => false,
        ));

        /** @var modUserGroup $userGroup */
        $userGroup = $this->modx->newObject('modUserGroup');
        $userGroup->fromArray(array(
            'name' => 'UnitTest UserGroup 1',
        ));
        $userGroup->save();
        $userGroup = $this->modx->newObject('modUserGroup');
        $userGroup->fromArray(array(
            'name' => 'UnitTest UserGroup 2',
        ));
        $userGroup->save();

        $_POST = array(
            'username' => 'unit.test.user',
            'password' => 'a test password',
            'email' => 'test@test.com',
            'nospam' => '',
            'submitVar' => 'unit-test-register-btn',
        );

        $this->controller = $this->login->loadController('Register');
        $this->controller->initialize();

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
        $this->processor = $this->controller->loadProcessor('Register');
        $this->processor->user =& $this->user;
        $this->processor->profile =& $this->profile;
    }

    public function tearDown() {
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array('username' => 'unit.test.user'));
        if ($user) {
            $user->remove();
        }
        $userGroups = $this->modx->getCollection('modUserGroup',array(
            'name:LIKE' => 'UnitTest%',
        ));
        /** @var $userGroup modUserGroup */
        foreach ($userGroups as $userGroup) {
            $userGroup->remove();
        }
        parent::tearDown();
        $this->user = null;
        $this->controller = null;
        $this->processor = null;
    }

    /**
     * Test the setting of user fields
     * @return void
     */
    public function testSetUserFields() {
        $this->processor->setUserFields();
        $this->assertEquals($_POST['username'],$this->processor->user->get('username'));
        $this->assertEquals(md5($_POST['password']),$this->processor->user->get('password'));
    }

    /**
     * @param string $userGroups
     * @param array $expected
     * @dataProvider providerSetUserGroups
     */
    public function testSetUserGroups($userGroups = '',array $expected = array()) {
        $this->controller->setProperty('usergroups',$userGroups);
        $addedUserGroups = $this->processor->setUserGroups();
        $this->assertEquals($expected,$addedUserGroups);
    }
    public function providerSetUserGroups() {
        return array(
            array('UnitTest UserGroup 1',array('UnitTest UserGroup 1')),
            array('UnitTest UserGroup 1,UnitTest UserGroup 2',array('UnitTest UserGroup 1','UnitTest UserGroup 2')),
            array('UnitTest UserGroup 1, UnitTest UserGroup 2',array('UnitTest UserGroup 1','UnitTest UserGroup 2')),
        );
    }

    /**
     * Test the stripping of non-essential fields from the data source
     * @return void
     */
    public function testCleanseFields() {
        $this->controller->loadDictionary();
        $this->processor->cleanseFields();
        $this->assertArrayHasKey('username',$this->controller->dictionary->toArray());
        $this->assertArrayNotHasKey('nospam',$this->controller->dictionary->toArray());
    }

    /**
     * Test the gathering of activation email properties
     * @return void
     */
    public function testGatherActivationEmailProperties() {
        $this->controller->loadDictionary();
        $properties = $this->processor->gatherActivationEmailProperties();
        $this->assertNotEmpty($properties);
    }

    /**
     * Assert email sending works
     * @return void
     */
    public function testSendActivationEmail() {
        $this->controller->loadDictionary();
        $sent = $this->processor->sendActivationEmail();
        $this->assertTrue($sent);
    }


    /**
     * Ensure that the check for moderation code works as expected
     * @param boolean $shouldPass
     * @param boolean $moderate
     * @dataProvider providerCheckForModeration
     */
    public function testCheckForModeration($shouldPass,$moderate) {
        $this->processor->dictionary->set('register.moderate',$moderate);
        $result = $this->processor->checkForModeration();
        $this->assertEquals($shouldPass,$result);
    }
    /**
     * @return array
     */
    public function providerCheckForModeration() {
        return array(
            array(true,true),
            array(false,false),
        );
    }

    /**
     * Ensure that the check for moderation redirect code works as expected
     * @param boolean $shouldPass
     * @param boolean $moderate
     * @dataProvider providerCheckForModerationRedirect
     */
    public function testCheckForModerationRedirect($shouldPass,$moderate) {
        $this->processor->dictionary->set('register.moderate',$moderate);
        $result = $this->processor->checkForModerationRedirect();
        $this->assertEquals($shouldPass,$result);
    }
    /**
     * @return array
     */
    public function providerCheckForModerationRedirect() {
        return array(
            array(true,true),
            array(false,false),
        );
    }

    /**
     * @param boolean $shouldPass
     * @param int $submittedResourceId
     * @dataProvider providerCheckForRegisteredRedirect
     */
    public function testCheckForRegisteredRedirect($shouldPass,$submittedResourceId) {
        $this->controller->setProperty('submittedResourceId',$submittedResourceId);
        $result = $this->processor->checkForRegisteredRedirect();
        $this->assertEquals($shouldPass,$result);
    }
    /**
     * @return array
     */
    public function providerCheckForRegisteredRedirect() {
        return array(
            array(true,1),
            array(false,''),
        );
    }

    /**
     * Attempt to run a file-based preHook and set the value of a field
     *
     * @depends testSetUserFields
     */
    public function testPostHooks() {
        $this->controller->setProperty('postHooks',$this->login->config['testsPath'].'Hooks/Post/posthooktest.register.php');
        $this->processor->setUserFields();
        $this->processor->setExtended();
        $this->processor->runPostHooks();
        $val = $this->controller->postHooks->getValue('fullname');
        $success = strcmp($val,'John Doe') == 0;
        $this->assertTrue($success,'The postHook was not fired or did not set the value of the field.');

        $val = $this->controller->postHooks->getValue('username');
        $success = strcmp($val,$_POST['username']) == 0;
        $this->assertTrue($success,'The postHook did not correctly pass register.user.');

        $val = $this->controller->postHooks->getValue('email');
        $success = strcmp($val,$_POST['email']) == 0;
        $this->assertTrue($success,'The postHook did not correctly pass register.profile.');
    }
}