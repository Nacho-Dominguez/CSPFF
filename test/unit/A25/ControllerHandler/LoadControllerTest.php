<?php

class test_unit_A25_ControllerHandler_LoadControllerTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function callsExecuteTaskAndReturnsTrue()
	{
		$handler = $this->createControllerHandler('Controller_LoadControllerTest_IsSubClass');

		$this->assertTrue($handler->loadController());
	}

	/**
	 * @test
	 */
	public function returnsFalseIfClassDoesNotExist()
	{
		$handler = $this->createControllerHandler('ClassNameTheDoesNotExist');

		$this->assertFalse($handler->loadController());
	}

	/**
	 * @test
	 */
	public function returnsFalseIfClassIsNotASubClassOfController()
	{
		$handler = $this->createControllerHandler('Controller_LoadControllerTest_IsNotSubClass');
		
		$this->assertFalse($handler->loadController());
	}

	private function createControllerHandler($generatedClassName)
	{
		$handler = $this->getMock('A25_ControllerHandler',
				array('generateClassName'), array(), '', false);
		$handler->expects($this->any())
				->method('generateClassName')
				->will($this->returnValue($generatedClassName));

		return $handler;
	}
}

class Controller_LoadControllerTest_IsNotSubClass
{ }

class Controller_LoadControllerTest_IsSubClass extends Controller
{
	public function executeTask(){}
}