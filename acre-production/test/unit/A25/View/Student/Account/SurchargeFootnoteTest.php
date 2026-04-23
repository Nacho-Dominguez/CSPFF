<?php

class test_unit_A25_View_Student_Account_SurchargeFootnoteTest extends
    test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function showsFootnoteIfNecessary()
  {
    $enroll = new A25_Record_Enroll();
    $order = new A25_Record_Order();
    $enroll->Order = $order;
    
    $surcharge = new A25_Record_OrderItem();
    $surcharge->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $surcharge->Order = $order;
    
    $account = new AccountWithSurchargeFootnoteExposed($enroll);
    $this->assertEquals($this->expectedOutput($surcharge->unit_price), $account->surchargeFootnote($surcharge->unit_price));
  }
  
	/**
	 * @test
	 */
	public function doesNotShowFootnoteIfNotNecessary()
  {
    $enroll = new A25_Record_Enroll();
    $order = new A25_Record_Order();
    $enroll->Order = $order;
    
    $account = new AccountWithSurchargeFootnoteExposed($enroll);
    $this->assertEquals(null, $account->surchargeFootnote());
  }
  
  private function expectedOutput($amount)
  {
    return '<p style="font-size: 10px; color: #999;">
    ** About the <i>DOR Fee</i> &ndash; ' . PlatformConfig::surchargeFootnote($amount) . 
    '</p>';
  }
}

class AccountWithSurchargeFootnoteExposed
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function surchargeFootnote()
  {
    return parent::surchargeFootnote();
  }
}
