<?php

class test_unit_A25_Record_Court_GetRevenueTest extends
		test_Framework_UnitTestCase
{
	private $court;

	public function setUp()
	{
		parent::setUp();
		$this->court = new A25_Record_Court();
	}

	/**
	 * @test
	 */
	public function returnsZero_whenNoEnrollments()
	{
		$this->assertEquals(0,$this->court->getRevenue());
	}
	/**
	 * @test
	 */
	public function returnsRevenue_whenPaidLineItem()
	{
		$item = $this->createUnpaidLineItem();
		$item->date_paid = '2010-07-19';

		$this->assertEquals($item->chargeAmount(),$this->court->getRevenue());
	}
	/**
	 * @test
	 */
	public function returnsZero_whenUnpaidLineItem()
	{
		$item = $this->createUnpaidLineItem();

		$this->assertEquals(0,$this->court->getRevenue());
	}
	/**
	 * @test
	 */
	public function returnsZero_whenPaidLineItemIsWavied()
	{
		$item = $this->createUnpaidLineItem();
		$item->date_paid = '2010-07-19';
		$item->waive();

		$this->assertEquals(0,$this->court->getRevenue());
	}
	/**
	 * @test
	 */
	public function returnsZero_whenPaidLineItemIsCourtSurcharge()
	{
		$item = $this->createUnpaidLineItem();
		$item->date_paid = '2010-07-19';
		$item->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;

		$this->assertEquals(0,$this->court->getRevenue());
	}

	private function createUnpaidLineItem()
	{
		$enroll = new A25_Record_Enroll();
		$order = new A25_Record_Order();
		$item = $order->createLineItem(A25_Record_OrderItemType::typeId_CourseFee, 30);
		$enroll->Order = $order;
		$this->court->Enrollments[] = $enroll;

		return $item;
	}
}