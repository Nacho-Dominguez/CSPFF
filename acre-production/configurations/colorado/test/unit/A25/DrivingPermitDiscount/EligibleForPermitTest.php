<?php
class unit_EligibleForPermitTest_A25_DrivingPermitDiscount
		extends A25_DrivingPermitDiscount
{
	public static function eligibleForPermit(A25_Record_Student $student,
			A25_Record_Course $course)
	{
		return parent::eligibleForPermit($student,$course);
	}
}

class test_unit_A25_DrivingPermitDiscount_EligibleForPermitTest
		extends test_Framework_UnitTestCase
{
	private $student;
	private $course;


	public function setUp()
	{
		$this->student = new A25_Record_Student();
		$this->student->date_of_birth = '1990-01-01';
		$this->student->license_status = A25_Record_Student::licenseStatus_unlicensed;

		$this->course = new A25_Record_Course();
		$this->course->course_start_date = date('Y-m-d', strtotime($this->student->date_of_birth . '+16 years'));
	}

	/**
	 * @test
	 */
	public function ifUnderAge17AndHasNotRecievedLicense()
	{
		$this->expect(true);
	}

	/**
	 * @test
	 */
	public function ifOverAge17AndHasNotRecievedLicense()
	{
		$this->course->course_start_date = date('Y-m-d', strtotime($this->student->date_of_birth . '+17 years'));

		$this->expect(false);
	}

	/**
	 * @test
	 */
	public function ifUnderAge17AndHasRecievedLicense()
	{
		$this->student->license_status = A25_Record_Student::licenseStatus_valid;

		$this->expect(false);
	}

	/**
	 * @test
	 */
	public function returnsFalse_whenStudentHasCompletedCourse()
	{
    $enroll = new A25_Record_Enroll();
    $course = new A25_Record_Course();
    $course->course_start_date = A25_Functions::formattedDateTime('yesterday');
    $enroll->Course = $course;
    $enroll->status_id = A25_Record_Enroll::statusId_completed;
		$this->student->Enrollments[] = $enroll;

		$this->expect(false);
	}

	private function expect($eligible)
	{
		$output = unit_EligibleForPermitTest_A25_DrivingPermitDiscount
				::eligibleForPermit($this->student, $this->course);
		$this->assertEquals($eligible, $output);
	}
}
