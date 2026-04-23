<?php

class test_unit_A25_DoctrineRecord_InverseRelationTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getsInverseOfRelation()
	{
		$enroll = new A25_Record_Enroll();
		$this->assertEquals('Enrollments',
			$enroll->inverseRelation($enroll->relationFor('Student'))->getAlias());

		// In the other direction:
		$student = new A25_Record_Student();
		$this->assertEquals('Student',
			$student->inverseRelation($student->relationFor('Enrollments'))->getAlias());
	}
	/**
	 * @test
	 */
	public function worksWithOrderToOrderItem()
	{
		$order = new A25_Record_Order();
		$this->assertEquals('Order',
			$order->inverseRelation($order->relationFor('OrderItems'))->getAlias());
		
		// In the other direction
		$item = new A25_Record_OrderItem();
		$this->assertEquals('OrderItems',
			$item->inverseRelation($item->relationFor('Order'))->getAlias());
	}
	/**
	 * @test
	 */
	public function worksWithOrderToPayment()
	{
		$order = new A25_Record_Order();
		$this->assertEquals('Order',
			$order->inverseRelation($order->relationFor('Payments'))->getAlias());

		// In the other direction
		$payment = new A25_Record_Pay();
		$this->assertEquals('Payments',
			$payment->inverseRelation($payment->relationFor('Order'))->getAlias());
	}
}