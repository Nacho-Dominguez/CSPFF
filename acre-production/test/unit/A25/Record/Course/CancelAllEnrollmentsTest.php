<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_CancelAllEnrollmentsTest
		extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function cancelsEnrollmentWhenCalled()
	{
		$enroll = new test_unit_A25_Record_Course_CancelAllEnrollmentsTest_A25_Record_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_student;
		$course = new A25_Record_Course();
		$course->Enrollments[] = $enroll;

		$course->cancelAllEnrollments();

		$this->assertEquals(A25_Record_Enroll::statusId_canceled, $enroll->status_id);
    }
}

class test_unit_A25_Record_Course_CancelAllEnrollmentsTest_A25_Record_Enroll
		extends A25_Record_Enroll
{
	public function save()
	{
	}
}