<?php

class test_unit_A25_CourseRunner_CheckIfEnrollmentAllowedTest extends
		test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function throwsExceptionIfCourseNotPublished()
  {
    $student = new A25_Record_Student();
    $student->date_of_birth = A25_Functions::formattedDateTime('-20 years');
    
    $course = new A25_Record_Course();
    $course->course_start_date = A25_Functions::formattedDateTime('1 day');
    $course->published = 0;
    
    $this->setExpectedException(A25_Exception_IllegalAction);
    
    $runner = new unit_checkIfEnrollmentAllowedTest_A25_CourseRunner($student, $course);
    $runner->checkIfEnrollmentAllowed();
  }
  
  /**
   * @test
   */
  public function noExceptionIfCourseIsPublished()
  {
    $student = new A25_Record_Student();
    $student->date_of_birth = A25_Functions::formattedDateTime('-20 years');
    
    $course = new A25_Record_Course();
    $course->course_start_date = A25_Functions::formattedDateTime('1 day');
    $course->published = 1;
    
    $runner = new unit_checkIfEnrollmentAllowedTest_A25_CourseRunner($student, $course);
    $runner->checkIfEnrollmentAllowed();
  }
}

class unit_checkIfEnrollmentAllowedTest_A25_CourseRunner extends
		A25_CourseRunner
{
	public function redirectIfAlreadyEnrolled()
	{
		return;
	}
}
