<?php

class test_unit_A25_Record_OrderItem_UpdateCalculatedValuesTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsIsActive()
	{
    $fee = new OrderItemStubbingIsActive();
    $fee->updateCalculatedValues();
    $this->assertEquals(true, $fee['calc_is_active']);
	}
  
  /**
	 * @test
	 */
	public function setsAccrualDate()
	{
    $fee = new OrderItemStubbingAccrualDate();
    $fee->updateCalculatedValues();
    $this->assertEquals('2011-01-01', $fee['calc_accrual_date']);
	}
}

class OrderItemStubbingIsActive extends A25_Record_OrderItem
{
  public function isActive()
  {
    // We are just calling it here to make sure it exists, so that if we rename
    // it later, this test will notice.  But the value it returns has no bearing
    // to our test; we just ignore it.
    parent::isActive();
    
    return true;
  }
}

class OrderItemStubbingAccrualDate extends A25_Record_OrderItem
{
  public function accrualDate()
  {
    // We are just calling it here to make sure it exists, so that if we rename
    // it later, this test will notice.  But the value it returns has no bearing
    // to our test; we just ignore it.
    parent::accrualDate();
    
    return '2011-01-01';
  }
}