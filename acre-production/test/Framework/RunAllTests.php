<?php

class test_Framework_RunAllTests {
	/**
	 * Test suite main method.
	 *
	 * @return void
	 */
	public static function inDirectory($dirname, $debug = false) {
		if (!defined('PHPUnit_MAIN_METHOD'))
			define('PHPUnit_MAIN_METHOD','AllTests');

		$arguments = array();
		
		if ($debug) {
			$arguments['debug'] = true;
		}

		PHPUnit_TextUI_TestRunner::run(self::suite($dirname), $arguments);
	}

	/**
	 * Creates the phpintegration test suite for this package.
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	private static function suite($dirname) {
		$testCollector = new PHPUnit_Runner_IncludePathTestCollector(
              array($dirname));

		$suite = new PHPUnit_Framework_TestSuite($dirname);
        $suite->addTestFiles($testCollector->collectTests());

		return $suite;
	}
}
