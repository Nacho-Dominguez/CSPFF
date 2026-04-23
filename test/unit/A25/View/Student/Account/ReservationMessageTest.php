<?php

class test_unit_A25_View_Student_Account_ReservationMessageTest extends
    test_Framework_UnitTestCase
{
  private $enroll;
  
  public function setUp()
  {
    parent::setUp();
    
    $location = new A25_Record_Location();
    $location->address_1 = '123 Fake Street';
    $location->city = 'Lakewood';
    $location->state = 'CO';
    $location->zip = '80226';
    
    $course = new A25_Record_Course();
    $course->Location = $location;
    $course->course_start_date = date('Y-m-d G:i:s', strtotime('+1 week'));
    $course->duration = strtotime('4 hours');
    
    $this->enroll = new A25_Record_Enroll();
    $this->enroll->Course = $course;
  }
	/**
	 * @test
	 */
	public function whenEnrollmentStatusIsRegisteredOutsideOneHour()
	{
    $this->enroll->status_id = A25_Record_Enroll::statusId_registered;
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime('1 day');
    
    $view = new ReservationMessageTest_AccountView($this->enroll);
    $this->assertEquals($view->reservationMessage(),
        '<div style="float: left; max-width: 325px; margin-bottom: 12px;">We have reserved a seat for you in this class. However, if'
        . ' payment is not received ' . $view->paymentDeadline() . ', you will lose your'
        . ' reservation.</div>');
	}
	/**
	 * @test
	 */
	public function whenEnrollmentStatusIsRegisteredWithinOneHour()
	{
    $this->enroll->status_id = A25_Record_Enroll::statusId_registered;
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime('59 minutes');
    
    $view = new ReservationMessageTest_AccountView($this->enroll);
    $this->assertContains('left to pay before seat reservation expires.', $view->reservationMessage());
	}
	/**
	 * @test
	 */
	public function whenEnrollmentStatusIsRegisteredButKickOutDateIsNull()
	{
    $this->enroll->status_id = A25_Record_Enroll::statusId_registered;
    
    $view = new ReservationMessageTest_AccountView($this->enroll);
    $this->assertEquals($view->reservationMessage(),
        'You are enrolled in the following class:');
	}
	/**
	 * @test
	 */
	public function whenEnrollmentStatusIsStudent()
	{
    $this->enroll->status_id = A25_Record_Enroll::statusId_student;
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime('1 day');
    
    $view = new ReservationMessageTest_AccountView($this->enroll);
    $this->assertEquals($view->reservationMessage(),
        'You are enrolled in the following class:');
	}
}

class ReservationMessageTest_AccountView
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function reservationMessage()
  {
    return parent::reservationMessage();
  }
  
  public function paymentDeadline()
  {
    return '(Payment deadline would be here)';
  }
}
