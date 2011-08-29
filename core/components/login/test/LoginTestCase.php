<?php
/**
 * @package login
 * @subpackage test
 */
/**
 * Extends the basic PHPUnit TestCase class to provide Login specific methods
 *
 * @package login
 * @subpackage test
 */
class LoginTestCase extends PHPUnit_Framework_TestCase {
    /**
     * @var modX $modx
     */
    protected $modx = null;
    /**
     * @var Login $login
     */
    protected $login = null;

    /**
     * Ensure all tests have a reference to the MODX and Quip objects
     */
    public function setUp() {
        $this->modx =& LoginTestHarness::_getConnection();
        $corePath = $this->modx->getOption('login.core_path',null,$this->modx->getOption('core_path',null,MODX_CORE_PATH).'components/login/');
        require_once $corePath.'model/login/login.class.php';
        $this->login = new Login($this->modx);
        /* set this here to prevent emails/headers from being sent */
        $this->login->inTestMode = true;
        /* make sure to reset MODX placeholders so as not to keep placeholder data across tests */
        $this->modx->placeholders = array();
        $this->modx->login =& $this->login;
        error_reporting(E_ALL);
    }

    /**
     * Remove reference at end of test case
     */
    public function tearDown() {
        $this->modx = null;
        $this->login = null;
    }
}