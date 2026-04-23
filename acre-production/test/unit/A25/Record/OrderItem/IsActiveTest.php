<?php

class test_unit_A25_Record_OrderItem_IsActiveTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function whenNonrefundable_isActive()
  {
    $fee = new NonrefundableOrderItem();

    $this->assertTrue($fee->isActive());
  }

  /**
   * @test
   */
  public function whenOrderIsActive_isActive()
  {
    $fee = new RefundableOrderItem();
    $order = new ActiveOrder();
    $fee->Order = $order;

    $this->assertTrue($fee->isActive());
  }

  /**
   * @test
   */
  public function whenOrderIsInactiveAndFeeIsRefundable_isInactive()
  {
    $fee = new RefundableOrderItem();
    $order = new InactiveOrder();
    $fee->Order = $order;

    $this->assertFalse($fee->isActive());
  }

  /**
   * @test
   */
  public function whenNoOrderAndFeeIsRefundable_isInactive()
  {
    $fee = new RefundableOrderItem();

    $this->assertFalse($fee->isActive());
  }
  
  /**
   * @test
   */
  public function whenWaived_isInactive()
  {
    $fee = new NonrefundableOrderItem();
    $fee->waive();

    $this->assertFalse($fee->isActive());
  }
}

class NonrefundableOrderItem extends A25_Record_OrderItem
{
  public function isIndependentOfEnrollment()
  {
    return true;
  }
}

class RefundableOrderItem extends A25_Record_OrderItem
{
  public function isIndependentOfEnrollment()
  {
    return false;
  }
}

class ActiveOrder extends A25_Record_Order
{
  public function isActive()
  {
    return true;
  }
}

class InactiveOrder extends A25_Record_Order
{
  public function isActive()
  {
    return false;
  }
}
