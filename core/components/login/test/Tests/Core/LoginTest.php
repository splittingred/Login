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
 * Tests related to Login snippet
 *
 * @package login
 * @subpackage test
 * @group Core
 * @group Login
 */
class LoginTest extends LoginTestCase {
    /** @var LoginLoginController */
    public $controller;

    public function setUp() {
        parent::setUp();
        $this->controller = $this->login->loadController('Login');
    }
    
    /**
     * Test the loading of reCaptcha
     * 
     * @param boolean $shouldLoad
     * @dataProvider providerLoadReCaptcha
     */
    public function testLoadReCaptcha($shouldLoad) {
        if ($shouldLoad) {
            $this->controller->setProperty('preHooks','recaptcha');
        }
        $loaded = $this->controller->loadReCaptcha();
        $this->assertEquals($shouldLoad,$loaded);
    }
    public function providerLoadReCaptcha() {
        return array(
            array(true),
            array(false),
        );
    }
}