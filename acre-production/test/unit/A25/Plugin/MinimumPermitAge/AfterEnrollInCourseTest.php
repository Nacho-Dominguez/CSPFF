<?php

require_once ServerConfig::webRoot . '/plugins/MinimumPermitAge.php';

class test_unit_A25_Plugin_MinimumPermitAge_AfterEnrollInCourseTest extends
		test_Framework_UnitTestCase
{
  private $plugin;
  private $student;
  private $course;
  private $enroll;
  public function setUp()
  {
    parent::setUp();
    
    $this->plugin = new A25_Plugin_MinimumPermitAge;
    $this->student = new A25_Record_Student();
    $this->student->date_of_birth = '1999-01-01';
    $this->course = new A25_Record_Course();
    $this->enroll = new A25_Record_Enroll();
    $this->enroll->Student = $this->student;
    $this->enroll->Course = $this->course;
  }
	/**
	 * @test
	 */
  public function allowsIfOldEnough()
  {
    $this->course->course_start_date = '2014-07-01';
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;
    
    ob_start();
    $this->plugin->afterEnrollInCourse($this->enroll);
    $result = ob_get_clean();
    $this->assertEquals(null, $result);
  }
	/**
	 * @test
	 */
  public function allowsIfTooYoungAndReasonIsNotPermit()
  {
    $this->course->course_start_date = '2014-06-30';
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_ParentsRequired;
    
    ob_start();
    $this->plugin->afterEnrollInCourse($this->enroll);
    $result = ob_get_clean();
    $this->assertEquals(null, $result);
  }
	/**
	 * @test
	 */
  public function deniesIfTooYoungAndReasonIsPermit()
  {
    $this->course->course_start_date = '2014-06-30';
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;
    
    $this->setExpectedException('A25_Exception_InvalidEntry',
        "This course does not fulfill the DMV's driver education requirement to obtain your driving permit. Please choose a different reason for enrollment.");
    $this->plugin->afterEnrollInCourse($this->enroll);
  }
}
