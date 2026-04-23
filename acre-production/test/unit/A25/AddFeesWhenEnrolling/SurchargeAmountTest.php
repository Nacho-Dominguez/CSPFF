<?php

class test_unit_A25_AddFeesWhenEnrolling_SurchargeAmountTest
		extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function ifEnrollmentNotMarkedCourtOrdered_0()
  {
    $enroll = new A25_Record_Enroll();
    $court = new A25_Record_Court();
    $court->surcharge_fee = 27;
    $enroll->Court = $court;

    $fee_assigner = new AddFeesWhenEnrollingExposingSurchargeAmount($enroll,
        new A25_Record_Order());

    $this->assertEquals(0, $fee_assigner->surchargeAmount());
  }

  /**
   * @test
   */
  public function ifNoCourtAssigned_0()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->reason_id = A25_Record_ReasonType::reasonTypeId_CourtOrdered;

    $fee_assigner = new AddFeesWhenEnrollingExposingSurchargeAmount($enroll,
        new A25_Record_Order());

    $this->assertEquals(0, $fee_assigner->surchargeAmount());
  }

  /**
   * @test
   */
  public function ifCourtOrderedStatusAndCourtAssigned_returnsAmount()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->reason_id = A25_Record_ReasonType::reasonTypeId_CourtOrdered;
    $court = new A25_Record_Court();
    $court->surcharge_fee = 27;
    $enroll->Court = $court;

    $fee_assigner = new AddFeesWhenEnrollingExposingSurchargeAmount($enroll,
        new A25_Record_Order());

    $this->assertEquals($court->surcharge_fee, $fee_assigner->surchargeAmount());
  }
}

class AddFeesWhenEnrollingExposingSurchargeAmount extends A25_AddFeesWhenEnrolling
{
	public function surchargeAmount()
	{
		return parent::surchargeAmount();
	}
}
