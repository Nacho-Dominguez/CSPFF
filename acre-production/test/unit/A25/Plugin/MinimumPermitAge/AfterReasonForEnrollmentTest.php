<?php

require_once ServerConfig::webRoot . '/plugins/MinimumPermitAge.php';

class test_unit_A25_Plugin_MinimumPermitAge_AfterReasonForEnrollmentTest extends
		test_Framework_UnitTestCase
{
  private $plugin;
  private $student;
  private $course;
  public function setUp()
  {
    parent::setUp();
    
    $this->plugin = new A25_Plugin_MinimumPermitAge;
    $this->student = new A25_Record_Student();
    $this->student->date_of_birth = '1999-01-01';
    $this->course = new A25_Record_Course();
  }
	/**
	 * @test
	 */
  public function showsNoCheckboxIfOldEnough()
  {
    $this->course->course_start_date = '2014-07-01';
    
    ob_start();
    $this->plugin->afterReasonForEnrollment($this->student, $this->course);
    $checkbox = ob_get_clean();
    $this->assertEquals(null, $checkbox);
  }
	/**
	 * @test
	 */
  public function showsCheckboxIfTooYoung()
  {
    $this->course->course_start_date = '2014-06-30';
    
    ob_start();
    $this->plugin->afterReasonForEnrollment($this->student, $this->course);
    $checkbox = ob_get_clean();
    $this->assertContains($this->expectedOutput(), $checkbox);
  }
  
  private function expectedOutput()
  {
    return 'I understand that, because I will be younger than 15 years, 6 months old on the course date, this course <b>does not</b> fulfill the DMV\'s driver education requirement to obtain my driving permit.';
  }
}
