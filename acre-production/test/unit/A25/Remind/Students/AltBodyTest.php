<?php

class test_unit_A25_Remind_Students_AltBodyTest extends
		test_Framework_UnitTestCase
/**
 * This test makes use of A25_Remind_Students_UpcomingClass in order to have a
 * concrete class, but it is actually testing altBody() in A25_Remind_Students
 */
{
  private $class;
  private $enroll;
  private $student;
  private $course;
  private $location;

  public function setUpTests($balance)
  {
    $this->class = new StudentsWithAltBodyExposed();

    $this->enroll = new A25_Record_Enroll();
    $this->student = $this->getMock('A25_Record_Student',
        array('getAccountBalance'));
    $this->student->expects($this->any())->method('getAccountBalance')
        ->will($this->returnValue($balance));
    $this->enroll->Student = $this->student;
    $this->enroll->kick_out_date = '2012-12-08 08:00:00';

    $this->course = new A25_Record_Course();
    $this->enroll->Course = $this->course;
    $this->course->course_start_date = '2012-12-31 08:00';
    $this->course->duration = '09:00:00';
    $this->course->course_description = 'Course description goes here';
    $this->course->late_fee = 10;

    $this->location = new A25_Record_Location();
    $this->enroll->Course->Location = $this->location;
  }

  /**
   * @test
   */
  public function fillsInBodyWithoutPaymentDueBoxWhenNoPaymentIsDue()
  {
    $this->setUpTests(0);
    $this->student->first_name = 'John';

    $this->location->location_name = "Somewhere";
    $this->location->address_1 = "123 Fake St";
    $this->location->address_2 = "Suite #1";
    $this->location->city = "Golden";
    $this->location->state = "CO";
    $this->location->zip = "80401";

    $this->assertEquals($this->expectedOutput(),
        $this->class->alt_body($this->enroll));
  }

  /**
   * @test
   */
  public function fillsInPaymentDueBoxWhenPaymentIsDue()
  {
    $this->setUpTests(79);
    $this->assertContains($this->paymentBox(),
        $this->class->alt_body($this->enroll));
  }

  private function expectedOutput()
  {
    return <<<END
Class reminder from Alive at 25 
www.aliveat25.us
(720) 269-4046
Manage your account online 

John, 

Just a friendly reminder about your upcoming Alive at 25 Driver's Awareness
Course. 

Monday, December 31, 2012
8:00 am - 5:00 pm

Somewhere
123 Fake St
Suite #1
Golden, CO 80401

Google Maps 


Please bring a photo ID (if available). Be sure to arrive early, as late
arrivals are not allowed to attend. 

Course description goes here 


If you are unable to attend this class, please cancel or reschedule your class
as soon as possible or at least 24 hours in advance. 

Thank you,

Alive at 25 


END;
  }

  private function paymentBox()
  {
    return <<<END
Course description goes here 


Amount due: $79


Pay now via credit card online

Or, mail money order payable to "Alive at 25" to: 

Alive At 25
55 Wadsworth Blvd
Lakewood, CO 80226


Please make sure payment arrives before 8:00 am on Saturday, December 8 or you
will lose your seat reservation in the class and will have to register again.
Please include the student's name and Alive at 25 student ID number (#) with
your payment. 

If your money order is not approved by your financial institution, a $10 late
fee and a $35 NSF charge will apply. 

All payments are non-refundable and cannot be transferred to another student's
account. However, if you cancel your enrollment and re-enroll in a different
class, your payment will automatically be applied to the new enrollment. 

If you have already mailed in payment, please disregard this message. 


If you are unable to attend this class, please cancel or reschedule your class
END;
  }
}

class StudentsWithAltBodyExposed extends A25_Remind_Students_UpcomingClass_FirstReminder
{
  public function alt_body($enroll) {
    return parent::alt_body($enroll);
  }
}
