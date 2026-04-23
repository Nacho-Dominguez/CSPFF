<?php

class test_unit_A25_Record_Order_TotalAmountTest extends
		test_Framework_UnitTestCase
{
  /**
   * @test 
   */
	public function whenNoOrderItems_ReturnsZero()
  {
    $order = new A25_Record_Order();
    $this->assertEquals(0, $order->totalAmount());
  }
  
  /**
   * @test 
   */
	public function withMultipleOrderItems_ReturnsSum()
  {
    $order = new A25_Record_Order();
    $fee1 = new A25_Record_OrderItem();
    $fee1->quantity = 1;
    $fee1->unit_price = 1;
    $fee2 = new A25_Record_OrderItem();
    $fee2->quantity = 1;
    $fee2->unit_price = 2;
    $order->OrderItems[] = $fee1;
    $order->OrderItems[] = $fee2;
    $this->assertEquals(3, $order->totalAmount());
  }
}
