<?php

class test_unit_A25_ControllerHandler_GenerateClassNameTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnsClassName()
	{
		$task = 'ThisIsATask';

		$handler = new test_unit_GenerateClassName_A25_ControllerHandler($task);

		$expectedClassName = 'Controller_' . $task;
		$this->assertEquals($expectedClassName, $handler->generateClassName());
	}
}

class test_unit_GenerateClassName_A25_ControllerHandler
		extends A25_ControllerHandler
{
	public function generateClassName()
	{
		return parent::generateClassName();
	}
}