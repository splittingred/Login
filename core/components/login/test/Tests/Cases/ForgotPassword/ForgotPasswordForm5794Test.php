<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * Test for issue #5794: http://bugs.modx.com/issues/5794
 * 
 * @package login
 * @subpackage test
 * @group Cases
 * @group ForgotPassword
 * @group Cases.ForgotPassword
 * @group Cases.ForgotPassword.5794
 * @group Issue-5794
 */
class ForgotPasswordForm5794Test extends LoginTestCase {
    /** @var LoginForgotPasswordController */
    public $controller;
    /** @var modUser $user */
    public $user;

    /**
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->controller = $this->login->loadController('Register');
        $this->controller->setProperties(array(
            'activation' => false,
            'preHooks' => '',
            'postHooks' => '',
            'submitVar' => 'registerbtn',
            'activation' => false,
        ));

        $_POST = array(
            'username' => 'unit-test-user-5794',
            'registerbtn' => 1,
            'email' => LoginTestHarness::$properties['email'],
            'password' => '123456789',
            'password_confirm' => '123456789',
            'nospam' => '',
        );
        $this->controller->loadDictionary();
        $this->controller->process();



        $this->controller = $this->login->loadController('ForgotPassword');
        $this->controller->setProperties(array(
            'preHooks' => '',
            'postHooks' => '',
            'submitVar' => 'login_fp',

            'resetResourceId' => 1,

        ));

        $_POST = array(
            'username' => 'unit-test-user-5794',
            'email' => '',
            'returnUrl' => '',
            'login_fp_service' => 'forgotpassword',
            'login_fp' => 1,
            'nospam' => '',
        );
        $this->controller->loadDictionary();
    }

    public function tearDown() {
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array(
            'username' => 'unit-test-user-5794',
        ));
        if ($user) {
            $user->remove();
        }
        parent::tearDown();
    }

    /**
     * Ensure only one forgot password email is sent
     *
     * @return void
     */
    public function testForm() {
        $this->controller->process();
        $errors = $this->controller->getPlaceholder('loginfp.errors');
        $this->assertEmpty($errors);
        $this->assertEquals(1,$this->controller->emailsSent);
    }
}