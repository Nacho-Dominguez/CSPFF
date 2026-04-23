<?php

define('_VALID_MOS',1);

class test_unit_A25_Record_CourseCheckEnrollmentTest extends
		test_Framework_UnitTestCase
{

	private function mySetUp($status)
	{
		$course = new A25_Record_Course();
		$enrollment = new A25_Record_Enroll();

		$enrollment->status_id = $status;

		$course->Enrollments[] = $enrollment;

		return $course;
	}

    /**
	 * @test
	 */
	public function registeredStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_registered);

		$this->assertEquals(false, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function studentStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_student);

		$this->assertEquals(false, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function completedStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_completed);

		$this->assertEquals(true, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function canceledStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_canceled);

		$this->assertEquals(true, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function kickedOutStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_kickedOut);

		$this->assertEquals(true, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function noShowStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_noShow);

		$this->assertEquals(true, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function unavailableStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_unavailable);

		$this->assertEquals(false, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function pendingStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_pending);

		$this->assertEquals(true, $course->checkEnrollment());
	}
	/**
	 * @test
	 */
	public function failedStatus()
	{
		$course = $this->mySetUp(A25_Record_Enroll::statusId_failed);

		$this->assertEquals(true, $course->checkEnrollment());
	}
}
?>
