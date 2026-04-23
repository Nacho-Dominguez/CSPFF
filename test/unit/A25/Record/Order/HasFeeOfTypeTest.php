<?php
class unit_HasFeeOfType_A25_Record_Order
		extends A25_Record_Order
{
	public function hasFeeOfType($type)
	{
		return parent::hasFeeOfType($type);
	}
}

class unit_hasFeeOfType_A25_Record_OrderItem
		extends A25_Record_OrderItem
{
	public $isActive = true;
	public function isActive()
	{
		return $this->isActive;
	}
}

class test_unit_A25_Record_Order_HasFeeOfTypeTest
		extends test_Framework_UnitTestCase
{
	private $item;
	private $order;

	public function setUp()
	{
		$this->item = new unit_hasFeeOfType_A25_Record_OrderItem();
		$this->item->type_id = A25_Record_OrderItemType::typeId_LateFee;

		$this->order = new unit_HasFeeOfType_A25_Record_Order();
		$this->order->OrderItems[] = $this->item;
	}
	/**
	 * @test
	 */
	public function returnsTrue_whenHasItem()
	{
		$this->expectOfType(true, $this->item->type_id);
	}
	/**
	 * @test
	 */
	public function returnsFalse_whenNoItemOfGivenType()
	{
		$this->expectOfType(false, $this->item->type_id+1);
	}
	/**
	 * @test
	 */
	public function returnsFalse_whenItemIsNotActive()
	{
		$this->item->isActive = false;
		$this->expectOfType(false, $this->item->type_id);
	}

	private function expectOfType($bool,$type)
	{
		$this->assertEquals($bool, $this->order->hasFeeOfType($type));
	}
}
