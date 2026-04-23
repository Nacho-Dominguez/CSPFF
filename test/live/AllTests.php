<?php
/**
 * Runs all of the tests in the test suite.
 *
 * @category Testing
 * @package AliveAt25
 * @author Thomas Albright
 */

ob_implicit_flush();

if (!defined('PHPUnit_MAIN_METHOD')) {
	define( 'PHPUnit_MAIN_METHOD', 'test_live_AllTests::main' );
}

require_once (dirname(__FILE__) . '/../../autoload.php');
require_once (dirname(__FILE__) . '/AdminTest.php');
require_once (dirname(__FILE__) . '/FrontendTest.php');

/**
 * Main test suite for AliveAt25
 *
 * @package aatTest
 * @author Thomas Albright
 */
class test_live_AllTests {
	/**
	 * Test suite main method.
	 *
	 * @return void
	 */
	public static function main() {
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	/**
	 * Creates the phpunit test suite for this package.
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('test_live');
		$suite->addTestSuite('test_live_AdminTest');
		$suite->addTestSuite('test_live_FrontendTest');

		return $suite;
	}
}

if ( PHPUnit_MAIN_METHOD == 'test_live_AllTests::main' ) {
	test_live_AllTests::main();
}
