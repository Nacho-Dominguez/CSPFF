<?php

class test_unit_A25_Injector_GetValueTest
		extends test_Framework_UnitTestCase
{
	private $injector;

	public function setUp()
	{
		$this->injector = new GetValueTest_unit_A25_Injector_Test();
		$this->injector->defaultValue = "defaultValue";
	}
	/**
	 * @test
	 */
	public function returnsDefaultValue_whenNoValueIsSet()
	{
		$this->assertEquals($this->injector->defaultValue,
				$this->injector->getValue());
	}

	/**
	 * @test
	 */
	public function returnsSetValue_whenValueIsSet()
	{
		$setValue = "Other Value";
		$this->injector->setValue($setValue);

		$this->assertEquals($setValue, $this->injector->getValue());
	}
}

class GetValueTest_unit_A25_Injector_Test
		extends A25_Injector
{
	public $defaultValue;

	protected function defaultValue()
	{
		return $this->defaultValue;
	}
}