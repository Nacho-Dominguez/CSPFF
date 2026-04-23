<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_OrderItem_ChargeAmountTest extends
		test_Framework_UnitTestCase
{
	private $lineitem;
	private $lineitem_amount;

	public function setUp()
	{
		parent::setUp();
		$this->lineitem = new A25_Record_OrderItem();
		$this->lineitem->quantity = 1;
		$this->lineitem->unit_price = 5;

		$this->lineitem_amount = 1*5;

		$this->lineitem->type_id = A25_Record_OrderItemType::typeId_CourseFee;
		$this->lineitem->Order = new A25_Record_Order();
	}
	/**
	 * @test
	 */
	public function returnsAmount_whenLineitemIsNotWaived()
	{
		$this->assertEquals($this->lineitem_amount,$this->lineitem->chargeAmount());
	}

	/**
	 * @test
	 */
	public function returnsZero_whenLineitemIsWaived()
	{
		$this->lineitem->waive();

		$this->assertEquals(0,$this->lineitem->chargeAmount());
	}
}
?>
