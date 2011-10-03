<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * Test for issue #5796: http://bugs.modx.com/issues/5796
 * 
 * @package login
 * @subpackage test
 * @group Cases
 * @group Register
 * @group Cases.Register
 * @group Cases.Register.5796
 * @group Issue-5796
 */
class RegisterForm5796Test extends LoginTestCase {
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
            'submitVar' => 'registerbtn',

            'activationResourceId' => 1,
            'activationEmailTpl' => $this->login->config['testsPath'].'Data/Register/5796-activation-email.chunk.tpl',
            'activationEmailSubject' => 'Thanks for Registering!',
            'submittedResourceId' => 1,
            'validate' => 'nospam:blank,
        username:required:minLength=^6^,
        password:required:minLength=^6^,
        password_confirm:password_confirm=^password^,
        fullname:required,
        email:required:email',
            'placeholderPrefix' => 'reg.',
        ));

        $_POST = array(
            'username' => 'unit-test-user-5796',
            'registerbtn' => 1,
            'email' => '',
            'password' => '',
            'nospam' => '',
        );
        $this->controller->loadDictionary();
    }

    public function tearDown() {
        /** @var modUser $user */
        $user = $this->modx->getObject('modUser',array(
            'username' => 'unit-test-user-5796',
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
        $this->assertTrue($this->login->controller->validator->hasErrorsInField('email'));
    }
}