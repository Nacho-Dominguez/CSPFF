<?php

class test_unit_A25_Record_Enroll_WaitingOnWaivedFeeConfirmationTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function whenOneFeeOfManyIsAwaitingApproval_returnsTrue()
  {
    $enroll = $this->getMock('EnrollWithWaivedFeeConfirmationExposed', array('lineItems'));
    $fee1 = new A25_Record_OrderItem();
    $fee2 = new UnconfirmedWaivedFee();
    $enroll->expects($this->any())->method('lineItems')
        ->will($this->returnValue(array($fee1, $fee2)));
    
    $this->assertTrue($enroll->waitingOnWaivedFeeConfirmation());
  }
  /**
   * @test
   */
  public function whenNoFeesOfManyAreAwaitingApproval_returnsFalse()
  {
    $enroll = $this->getMock('EnrollWithWaivedFeeConfirmationExposed', array('lineItems'));
    $fee1 = new A25_Record_OrderItem();
    $fee2 = new A25_Record_OrderItem();
    $enroll->expects($this->any())->method('lineItems')
        ->will($this->returnValue(array($fee1, $fee2)));
    
    $this->assertFalse($enroll->waitingOnWaivedFeeConfirmation());
  }
  /**
   * @test
   */
  public function whenNoFeesExist_returnsFalse()
  {
    $enroll = $this->getMock('EnrollWithWaivedFeeConfirmationExposed', array('lineItems'));
    $enroll->expects($this->any())->method('lineItems')
        ->will($this->returnValue(null));
    $this->assertFalse($enroll->waitingOnWaivedFeeConfirmation());
  }
  /**
   * @test
   */
  public function whenFeeIsWaivedAndConfirmed_returnsFalse()
  {
    $enroll = $this->getMock('EnrollWithWaivedFeeConfirmationExposed', array('lineItems'));
    $fee = new WaivedAndConfirmedFee();
    $enroll->expects($this->any())->method('lineItems')
        ->will($this->returnValue(array($fee)));
    $this->assertFalse($enroll->waitingOnWaivedFeeConfirmation());
  }
}

class WaivedAndConfirmedFee extends A25_Record_OrderItem
{
	public function __construct()
	{
	}
	public function waivedButUnconfirmed()
	{
		return false;
	}
}

class UnconfirmedWaivedFee extends A25_Record_OrderItem
{
	public function __construct()
	{
	}
	public function waivedButUnconfirmed()
	{
		return true;
	}
}

class EnrollWithWaivedFeeConfirmationExposed extends A25_Record_Enroll
{
  public function waitingOnWaivedFeeConfirmation() {
    return parent::waitingOnWaivedFeeConfirmation();
  }
}