<?php

class test_unit_A25_Record_Order_IsActiveTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function withActiveEnrollment_isActive()
  {
    $order = new A25_Record_Order();
    $order->Enrollment = new ActiveEnrollment();

    $this->assertTrue($order->isActive());
  }

  /**
   * @test
   */
  public function withInactiveEnrollment_isInactive()
  {
    $order = new A25_Record_Order();
    $order->Enrollment = new InactiveEnrollment();

    $this->assertFalse($order->isActive());
  }

  /**
   * @test
   */
  public function withNoEnrollment_isInactive()
  {
    $order = new A25_Record_Order();

    $this->assertFalse($order->isActive());
  }
}

class ActiveEnrollment extends A25_Record_Enroll
{
  public function isActive()
  {
    return true;
  }
}

class InactiveEnrollment extends A25_Record_Enroll
{
  public function isActive()
  {
    return false;
  }
}
