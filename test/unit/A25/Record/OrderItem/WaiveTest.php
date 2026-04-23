<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_OrderItem_WaiveTest extends
		test_Framework_UnitTestCase
{
	private $lineitem;

	public function setUp()
	{
		parent::setUp();
		$this->lineitem = new A25_Record_OrderItem();
		$order = $this->getMock('A25_Record_Order', array('updateTotal'));
		$this->lineitem->Order = $order;
	}
	/**
	 * @test
	 */
	public function waiveDateIsSetCorrectly_afterFunctionIsCalled()
	{
		$this->lineitem->waive();

		$this->assertEquals(date('Y-m-d'), $this->lineitem->waive_date);
	}
	/**
	 * @test
	 */
	public function waiveTypeIsSetToAdmin_afterFunctionIsCalled()
	{
		$this->lineitem->waive();

		$this->assertEquals(A25_Record_OrderItem::waiveType_Admin,
				$this->lineitem->waive_type);
	}
	/**
	 * @test
	 */
	public function waiveTypeIsSetToStudent_afterFunctionIsCalledForStudent()
	{
		$this->lineitem->waive(A25_Record_OrderItem::waiveType_Student_SelfSend);

		$this->assertEquals(A25_Record_OrderItem::waiveType_Student_SelfSend,
				$this->lineitem->waive_type);
	}
}