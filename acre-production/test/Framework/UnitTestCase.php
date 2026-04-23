<?php

abstract class test_Framework_UnitTestCase extends test_Framework_TestCase
{
  protected $original_include_path;
  
	public function setUp()
	{
		A25_DI::reset();
		A25_DoctrineRecord::$disableSave = true;
    util_Mysql::$disableModifications = true;

		$this->original_include_path = get_include_path();
	}
  public function tearDown()
  {
		set_include_path($this->original_include_path);
  }
}
