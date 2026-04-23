<?php

class test_unit_A25_AddFeesWhenEnrolling_AlreadyHasSurchargeTest
		extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function whenOnlyEnrollment_returnsFalse()
  {
    $enroll = new FirstEnrollment();
    $fee_assigner = new AddFeesWhenEnrollingExposingAlreadyHasSurcharge($enroll,
        new A25_Record_Order());

    $this->assertFalse($fee_assigner->alreadyHasSurcharge());
  }

  /**
   * @test
   */
  public function whenPreviousEnrollmentHasSurcharge_returnsTrue()
  {
    $prev = $this->mock('A25_Record_Enroll');
    $surcharge = $this->mock('A25_Record_OrderItem');
    $surcharge->expects($this->any())->method('isActive')->will($this->returnValue(true));
    $prev->expects($this->any())->method('surchargeLineItem')
        ->will($this->returnValue($surcharge));
    $prev->expects($this->any())->method('hasBeenAttended')->will($this->returnValue(false));

    $enroll = $this->mock('A25_Record_Enroll');
    $enroll->expects($this->any())->method('previousEnrollment')->will($this->returnValue($prev));

    $fee_assigner = new AddFeesWhenEnrollingExposingAlreadyHasSurcharge($enroll,
        new A25_Record_Order());

    $this->assertTrue($fee_assigner->alreadyHasSurcharge());
  }

  /**
   * @test
   */
  public function whenPreviousEnrollmentHasSurchargeButIsComplete_returnsFalse()
  {
    $prev = $this->mock('A25_Record_Enroll');
    $surcharge = $this->mock('A25_Record_OrderItem');
    $surcharge->expects($this->any())->method('isComplete')->will($this->returnValue(true));
    $prev->expects($this->any())->method('surchargeLineItem')
        ->will($this->returnValue($surcharge));
    $prev->expects($this->any())->method('hasBeenAttended')->will($this->returnValue(true));

    $enroll = $this->mock('A25_Record_Enroll');
    $enroll->expects($this->any())->method('previousEnrollment')->will($this->returnValue($prev));

    $fee_assigner = new AddFeesWhenEnrollingExposingAlreadyHasSurcharge($enroll,
        new A25_Record_Order());

    $this->assertFalse($fee_assigner->alreadyHasSurcharge());
  }

  /**
   * @test
   */
  public function whenPreviousEnrollmentHasWaivedSurcharge_returnsFalse()
  {
    $prev = $this->mock('A25_Record_Enroll');
    $surcharge = $this->mock('A25_Record_OrderItem');
    $surcharge->expects($this->any())->method('isActive')->will($this->returnValue(false));
    $prev->expects($this->any())->method('surchargeLineItem')
        ->will($this->returnValue($surcharge));
    $prev->expects($this->any())->method('hasBeenAttended')->will($this->returnValue(false));

    $enroll = $this->mock('A25_Record_Enroll');
    $enroll->expects($this->any())->method('previousEnrollment')->will($this->returnValue($prev));

    $fee_assigner = new AddFeesWhenEnrollingExposingAlreadyHasSurcharge($enroll,
        new A25_Record_Order());

    $this->assertFalse($fee_assigner->alreadyHasSurcharge());
  }
}

class AddFeesWhenEnrollingExposingAlreadyHasSurcharge
		extends A25_AddFeesWhenEnrolling
{
	public function alreadyHasSurcharge()
	{
		return parent::alreadyHasSurcharge();
	}
}

class FirstEnrollment extends A25_Record_Enroll
{
  public function previousEnrollment()
  {
    return null;
  }
}
