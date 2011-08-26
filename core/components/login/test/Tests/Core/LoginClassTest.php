<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * Tests related to basic Login class methods
 *
 * @package login
 * @subpackage test
 * @group Core
 */
class LoginClassTest extends LoginTestCase {
    /**
     * Test the loading of a controller
     * @return void
     */
    public function testLoadController() {
        $controller = $this->login->loadController('Login');
        $this->assertInstanceOf('LoginController',$controller);
        $this->assertInstanceOf('LoginController',$this->login->controller);
        $this->login->controller = null;
    }
}