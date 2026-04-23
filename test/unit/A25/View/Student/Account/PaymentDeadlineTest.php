<?php

class test_unit_A25_View_Student_Account_PaymentDeadlineTest extends
    test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function moreThanOneDayAway()
	{
    $enroll = new A25_Record_Enroll();
    $kick_out_time = strtotime('7 days');
    $enroll->kick_out_date = A25_Functions::formattedDateTime($kick_out_time);
    
    $view = new PaymentDeadlineTest_AccountView($enroll);
    $this->assertEquals('by ' . date('g:i a', $kick_out_time)
        . ' on <b>' . date('l, F j', $kick_out_time) . '</b>', 
        $view->paymentDeadline());
	}
	/**
	 * @test
	 */
	public function lessThanOneDayAway()
	{
    $enroll = new A25_Record_Enroll();
    $enroll->kick_out_date = A25_Functions::formattedDateTime(strtotime('23 hours 59 minutes'));
    
    $timezone = date_default_timezone_get();
    
    $view = new PaymentDeadlineTest_AccountView($enroll);
    $this->assertEquals('within the next 23 hours', 
        $view->paymentDeadline());
    // Make sure that timezone did not change permanently:
    $this->assertEquals($timezone, date_default_timezone_get());
	}
	/**
	 * @test
	 */
	public function lessThanOneHourAway()
	{
    $enroll = new A25_Record_Enroll();
    $enroll->kick_out_date = A25_Functions::formattedDateTime(strtotime('59 minutes 59 seconds'));
    
    $timezone = date_default_timezone_get();
    
    $view = new PaymentDeadlineTest_AccountView($enroll);
    $this->assertEquals('within the next 59 minutes', 
        $view->paymentDeadline());
    // Make sure that timezone did not change permanently:
    $this->assertEquals($timezone, date_default_timezone_get());
	}
}

class PaymentDeadlineTest_AccountView
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function paymentDeadline()
  {
    return parent::paymentDeadline();
  }
}
