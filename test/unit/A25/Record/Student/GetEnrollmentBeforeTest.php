<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_Student_GetEnrollmentBeforeTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function IfOnlyLaterEnrollment_ReturnsNull()
	{
		$student = new A25_Record_Student();
		$enroll = new A25_Record_Enroll();
		$enroll->xref_id = 1;
		$laterEnroll = new A25_Record_Enroll();
		$laterEnroll->xref_id = 2;
		$student->Enrollments[] = $enroll;
		$student->Enrollments[] = $laterEnroll;
		$this->assertNull($student->getEnrollmentBefore($enroll)->xref_id);
	}
	/**
	 * @test
	 */
	public function If2EarlierEnrollments_ReturnsMoreRecent()
	{
		$student = new A25_Record_Student();
		$earliestEnroll = new A25_Record_Enroll();
		$earliestEnroll->xref_id = 1;
		$previousEnroll = new A25_Record_Enroll();
		$previousEnroll->xref_id = 2;
		$enroll = new A25_Record_Enroll();
		$enroll->xref_id = 3;
		$student->Enrollments[] = $earliestEnroll;
		$student->Enrollments[] = $previousEnroll;
		$student->Enrollments[] = $enroll;
		$this->assertEquals($previousEnroll->xref_id,
				$student->getEnrollmentBefore($enroll)->xref_id);
	}
}
?>
