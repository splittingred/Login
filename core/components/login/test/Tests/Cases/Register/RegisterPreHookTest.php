<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * Creates a test for preHooks on a register form.
 *
 * @package login
 * @subpackage test
 * @group Cases
 * @group Cases.Register
 * @group Cases.Register.PreHook
 * @group Hooks
 */
class RegisterPreHookTest extends LoginTestCase {
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
            'usergroups' => '',
            'validate' => 'nospam:blank',
        ));
        $this->controller->loadDictionary();
    }

    /**
     * Attempt to run a file-based preHook and set the value of a field
     * 
     * @return void
     */
    public function testPreHooks() {
        $this->controller->setProperty('preHooks',$this->login->config['testsPath'].'Hooks/Pre/prehooktest.setvalue.php');
        $this->controller->loadPreHooks();
        $val = $this->controller->preHooks->getValue('fullname');
        $success = strcmp($val,'TestPreValue') == 0;
        $this->assertTrue($success,'The preHook was not fired or did not set the value of the field.');
    }
}