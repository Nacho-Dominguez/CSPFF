<?php

class test_unit_A25_Record_Student_EnrollInCourseTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsSentPaymentReminderIfAfterPaymentOptionDeadline()
	{
    $course = new A25_Record_Course();
    $course->register_cc_days = '15';
    $course->course_start_date = A25_Functions::formattedDateTime('15 days - 1 hour');
    
    $student = new A25_Record_Student();
    $enrollment = $student->enrollInCourse($course, 1, 2);
    $this->assertEquals(2, $enrollment->sent_payment_reminder);
	}
  
	/**
	 * @test
	 */
	public function doesNotSetSentPaymentReminderIfBeforePaymentOptionDeadline()
	{
    $course = new A25_Record_Course();
    $course->register_cc_days = '15';
    $course->course_start_date = A25_Functions::formattedDateTime('15 days + 1 hour');
    
    $student = new A25_Record_Student();
    $enrollment = $student->enrollInCourse($course, 1, 2);
    $this->assertEquals(0, $enrollment->sent_payment_reminder);
	}
}
