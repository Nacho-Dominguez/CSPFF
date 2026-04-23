<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_OrderItem_UnwaiveTest extends
		test_Framework_UnitTestCase
{
	private $lineitem;

	public function setUp()
	{
		parent::setUp();
		$this->lineitem = new A25_Record_OrderItem();
		$order = $this->getMock('A25_Record_Order', array('updateTotal'));
		$this->lineitem->Order = $order;
		$this->lineitem->waive();
	}
	/**
	 * @test
	 */
	public function waiveDateIsSetCorrectly_afterFunctionIsCalled()
	{
		$this->lineitem->unwaive();

		$this->assertNull($this->lineitem->waive_date);
	}
	/**
	 * @test
	 */
	public function waiveTypeIsSetToAdmin_afterFunctionIsCalled()
	{
		$this->lineitem->unwaive();

		$this->assertNull($this->lineitem->waive_type);
	}
}
