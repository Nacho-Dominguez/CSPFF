<?php
/**
 * Runs all of the unit and integration tests in the test suite.
 *
 * @category Testing
 * @package AliveAt25
 * @author Thomas Albright
 */

require_once(dirname(__FILE__) . '/../autoload.php');

if ( defined( 'PHPUnit_MAIN_METHOD' ) === false ) {
	define( 'PHPUnit_MAIN_METHOD', 'test_AllTests::main' );
}

require_once(dirname(__FILE__) . '/integration/AllTests.php');
require_once(dirname(__FILE__) . '/unit/AllTests.php');

/**
 * Main test suite for AliveAt25
 *
 * @package aatTest
 * @author Thomas Albright
 */
class test_AllTests {
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
		$suite = new PHPUnit_Framework_TestSuite('test');
		$suite->addTest( test_integration_AllTests::suite() );
		$suite->addTest( test_unit_AllTests::suite() );

		return $suite;
	}
}

if ( PHPUnit_MAIN_METHOD === 'test_AllTests::main' ) {
	test_AllTests::main();
}
