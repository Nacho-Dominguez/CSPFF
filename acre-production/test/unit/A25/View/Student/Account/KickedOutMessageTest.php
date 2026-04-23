<?php

class test_unit_A25_View_Student_Account_KickedOutMessageTest extends
    test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function excludesMailedInPayment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->date_registered = A25_Functions::formattedDateTime('-14 days');

    $course = new A25_Record_Course();
    $course->course_start_date = '2013-02-01';
    $course->register_cc_days = 15;
    $enroll->Course = $course;

    $account = new AccountWithKickedOutMessageExposed($enroll);
    $this->assertEquals($this->expectedOutput(), $account->kickedOutMessage());
  }

	/**
	 * @test
	 */
	public function includesMailedInPayment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->date_registered = A25_Functions::formattedDateTime('-16 days');

    $course = new A25_Record_Course();
    $course->course_start_date = '2013-02-01';
    $course->register_cc_days = 15;
    $enroll->Course = $course;

    $expected = $this->expectedOutput() . '<p>
      If you have already mailed in payment, please call our office at (720) 269-4046.
      </p>';

    $account = new AccountWithKickedOutMessageExposed($enroll);
    $this->assertEquals($expected, $account->kickedOutMessage());
  }

  private function expectedOutput()
  {
    return '<p>
    Your seat reservation has expired for the Alive at 25 Driver\'s Awareness course on Friday, February 1 because payment has not been received.  You may <a href="' . ServerConfig::staticHttpUrl() . 'find-a-course">register again for the same course or a different course</a>.  Please be sure to submit payment in time to preserve your seat in the course.
    </p>';
  }
}

class AccountWithKickedOutMessageExposed
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }

  public function kickedOutMessage()
  {
    return parent::kickedOutMessage();
  }
}
