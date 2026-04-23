<?php
class test_unit_A25_Form_ElementTest extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function constructorSetsDefaultLabel()
	{
		$credit_type_name = new A25_Form_Element_Text('credit_type_name');
		$this->assertEquals('Credit Type Name', $credit_type_name->getLabel());
	}
}
?>
