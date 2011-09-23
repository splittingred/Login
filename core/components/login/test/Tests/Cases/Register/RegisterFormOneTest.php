<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * [[!Register? &activation=`0` &submittedResourceId=`65` &usernameField=`email` &emailField=`email`]]
 *
 * @package login
 * @subpackage test
 * @group Cases
 * @group Register
 * @group Cases.Register
 * @group Cases.Register.FormOne
 */
class RegisterFormOneTest extends LoginTestCase {
    /** @var LoginRegisterController */
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
            'submitVar' => 'unit-test-register-btn',
            'submittedResourceId' => 1,
            'usernameField' => 'email',
            'emailField' => 'email',
            'validate' => 'nospam:blank',
        ));

        $_POST = array(
            'unit-test-register-btn' => 1,
            'email' => 'shaun+test@modx.com',
            'password' => '123456789',
            'nospam' => '',
        );
        $this->controller->loadDictionary();
    }

    public function tearDown() {
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array(
            'username' => 'shaun+test@modx.com',
        ));
        if ($user) {
            $user->remove();
        }
        parent::tearDown();
    }

    /**
     * Attempt to run a file-based preHook and set the value of a field
     *
     * @return void
     */
    public function testForm() {
        $this->controller->process();
        $exists = $this->modx->getCount('modUser',array(
            'username' => 'shaun+test@modx.com',
        ));
        $this->assertTrue($exists > 0);
    }
}