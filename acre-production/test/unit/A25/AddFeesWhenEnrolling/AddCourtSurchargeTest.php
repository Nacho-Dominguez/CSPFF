<?php

class test_unit_A25_AddFeesWhenEnrolling_AddCourtSurchargeTest
		extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function skipsWhenSurchargeIsZero()
  {
    $order = $this->getMock('A25_Record_Order');
    $order->expects($this->never())->method('createLineItem');
    $fee_assigner = $this->getMock('AddFeesWhenEnrollingExposingAddCourtSurcharge',
        array('surchargeAmount'), array(new A25_Record_Enroll(), $order));
    $fee_assigner->expects($this->any())->method('surchargeAmount')->will($this->returnValue(0));

    $fee_assigner->addCourtSurcharge();
  }

  /**
   * @test
   */
  public function skipsIfAlreadyHasSurcharge()
  {
    $order = $this->getMock('A25_Record_Order');
    $order->expects($this->never())->method('createLineItem');
    $fee_assigner = $this->getMock('AddFeesWhenEnrollingExposingAddCourtSurcharge',
        array('surchargeAmount', 'alreadyHasSurcharge'),
        array(new A25_Record_Enroll(), $order));
    $fee_assigner->expects($this->any())->method('surchargeAmount')->will($this->returnValue(25));
    $fee_assigner->expects($this->any())->method('alreadyHasSurcharge')->will($this->returnValue(true));

    $fee_assigner->addCourtSurcharge();
  }

  /**
   * @test
   */
  public function createsWithCorrectAmount()
  {

    $order = $this->getMock('A25_Record_Order');
    $order->expects($this->once())->method('createLineItem')->with(
        A25_Record_OrderItemType::typeId_CourtSurcharge, 27);
    $fee_assigner = $this->getMock('AddFeesWhenEnrollingExposingAddCourtSurcharge',
        array('surchargeAmount', 'alreadyHasSurcharge'),
        array(new A25_Record_Enroll(), $order));
    $fee_assigner->expects($this->any())->method('surchargeAmount')->will($this->returnValue(27));
    $fee_assigner->expects($this->any())->method('alreadyHasSurcharge')->will($this->returnValue(false));

    $fee_assigner->addCourtSurcharge();
  }
}

class AddFeesWhenEnrollingExposingAddCourtSurcharge
		extends A25_AddFeesWhenEnrolling
{
	public function addCourtSurcharge()
	{
		return parent::addCourtSurcharge();
	}
}
