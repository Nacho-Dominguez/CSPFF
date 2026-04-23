<?php

class test_unit_A25_View_Student_Account_LateFeeFootnoteTest extends
    test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function showsFootnoteIfNecessary()
  {
    $enroll = new A25_Record_Enroll();
    $course = new A25_Record_Course();
    $course->late_fee_deadline = 48;
    $course->late_fee = 10;
    $course->course_start_date = A25_Functions::formattedDateTime('47 hours');
    $enroll->Course = $course;
    
    $account = new AccountWithLateFeeFootnoteExposed($enroll);
    $this->assertEquals($this->expectedOutput($course), $account->lateFeeFootnote());
  }
  
	/**
	 * @test
	 */
	public function doesNotShowFootnoteIfNotNecessary()
  {
    $enroll = new A25_Record_Enroll();
    $course = new A25_Record_Course();
    $course->late_fee_deadline = 48;
    $course->course_start_date = A25_Functions::formattedDateTime('49 hours');
    $enroll->Course = $course;
    
    $account = new AccountWithLateFeeFootnoteExposed($enroll);
    $this->assertEquals(null, $account->lateFeeFootnote());
  }
  
  private function expectedOutput($course)
  {
    return '<p style="font-size: 10px; color: #999;">* A late fee of $10 applies to any payment that occurs within '
    . $course->getSetting('late_fee_deadline')
    . ' hours of the course or later.</p>';
  }
}

class AccountWithLateFeeFootnoteExposed
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function lateFeeFootnote()
  {
    return parent::lateFeeFootnote();
  }
}
