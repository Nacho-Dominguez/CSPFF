<?php

//test_Framework_RunAllTests::inDirectory(dirname(__FILE__));

class test_unit_AllTests
{

	/**
	 * Creates the phpunit test suite for this package.
	 *
	 * @return PHPUnit_Framework_TestSuite
	 */
	public static function suite()
	{
		$dirname = dirname(__FILE__);
		$testCollector = new PHPUnit_Runner_IncludePathTestCollector(
              array($dirname));

		$suite = new PHPUnit_Framework_TestSuite($dirname);
        $suite->addTestFiles($testCollector->collectTests());

		return $suite;
	}
}