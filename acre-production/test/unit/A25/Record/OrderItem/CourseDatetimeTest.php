<?php

class test_unit_A25_Record_OrderItem_CourseDatetimeTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function ifNoOrder_returnsNull()
	{
    $orderItem = new A25_Record_OrderItem();
    
    // Notice that we did not create an Order for the Order Item
    
    $this->assertNull($orderItem->courseDatetime());
	}
  
	/**
	 * @test
	 */
	public function returnsCourseDateOfItsOrder()
	{
    $orderItem = new A25_Record_OrderItem();
    $order = new Order4TestingCourseDate();
    $orderItem->Order = $order;
    
    $expectedCourseDate4OrderItem = $order->courseDatetime();
    $this->assertEquals($expectedCourseDate4OrderItem, $orderItem->courseDatetime());
	}
}

class Order4TestingCourseDate extends A25_Record_Order
{
  public function courseDatetime()
  {
    return '2012-01-01';
  }
}
