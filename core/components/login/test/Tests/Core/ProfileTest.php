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
 * Tests related to Profile snippet
 *
 * @package login
 * @subpackage test
 * @group Core
 * @group Profile
 */
class ProfileTest extends LoginTestCase {
    /** @var LoginProfileController */
    public $controller;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->login->loadController('Profile');
    }

    /**
     * Test the getUser method
     * 
     * @param boolean $shouldPass
     * @param int $user The ID of the user to fetch
     * @dataProvider providerGetUser
     */
    public function testGetUser($shouldPass,$user = 1) {
        $this->controller->setProperty('user',$user);
        $user = $this->controller->getUser();
        if ($shouldPass) {
            $this->assertInstanceOf('modUser',$user);
        } else {
            $this->assertFalse($user);
        }
    }
    /**
     * @return array
     */
    public function providerGetUser() {
        return array(
            array(true,1),
            array(false,1032423432),
        );
    }

    /**
     * Test LoginProfileController::getProfile
     * @depends testGetUser
     */
    public function testGetProfile() {
        $this->controller->getUser();
        $profile = $this->controller->getProfile();
        $this->assertInstanceOf('modUserProfile',$profile);
    }

    /**
     * Ensure that the proper placeholders are set, and that the prefix option is respected
     *
     * @param string $prefix
     * @dataProvider providerSetToPlaceholders
     * @depends testGetProfile
     * @depends testGetUser
     */
    public function testSetToPlaceholders($prefix = '') {
        $this->controller->setProperty('prefix',$prefix);
        $this->controller->getUser();
        $this->controller->getProfile();
        $placeholders = $this->controller->setToPlaceholders();
        $this->assertNotEmpty($placeholders);

        $this->assertArrayHasKey($prefix.'username',$this->modx->placeholders);
    }
    /**
     * @return array
     */
    public function providerSetToPlaceholders() {
        return array(
            array(''),
            array('up.'),
        );
    }

    /**
     * Test the getExtended method, ensuring it properly loads or does not load values
     *
     * @param boolean $shouldNotBeEmpty
     * @dataProvider providerGetExtended
     * @depends testGetProfile
     * @depends testGetUser
     */
    public function testGetExtended($shouldNotBeEmpty) {
        $this->controller->getUser();
        $this->controller->getProfile();
        $this->controller->profile->set('extended',array('test' => 1));
        $extended = $this->controller->getExtended();
        if ($shouldNotBeEmpty) {
            $this->assertInternalType('array',$extended);
            $this->assertNotEmpty($extended);
        } else {
            $this->assertEmpty($extended);
        }
    }
    /**
     * @return array
     */
    public function providerGetExtended() {
        return array(
            array(true),
        );
    }

    /**
     * Ensure the secure placeholders are being removed
     * 
     * @depends testGetProfile
     * @depends testGetUser
     * @depends testSetToPlaceholders
     */
    public function testRemovePasswordPlaceholders() {
        $this->controller->getUser();
        $this->controller->getProfile();
        $phs = array('username' => 'test','password' => 'bad','cachepwd' => 2);
        $phs = $this->controller->removePasswordPlaceholders($phs);
        $this->assertArrayNotHasKey('password',$phs,'removePasswordPlaceholders did not remove the password index.');
        $this->assertArrayNotHasKey('cachepwd',$phs,'removePasswordPlaceholders did not remove the cachepwd index.');
    }
}