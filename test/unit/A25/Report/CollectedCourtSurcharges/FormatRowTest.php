<?php
class test_unit_A25_Report_CollectedCourtSurcharges_FormatRowTest_CourtSurcharge
		extends A25_Report_CollectedCourtSurcharges
{
	public function __construct()
	{
	}
	public function formatRow(A25_DoctrineRecord $lineitem)
	{
		return parent::formatRow($lineitem);
	}
}

class test_unit_A25_Report_CollectedCourtSurcharges_FormatRowTest_OrderItem
		extends A25_Record_OrderItem
{
	public function dateOfCompletedCourse()
	{
		return '2011-01-01';
	}
}

class test_unit_A25_Report_CollectedCourtSurcharges_FormatRowTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnsExpectedArray()
	{
		$enroll = new A25_Record_Enroll();
		$student = new A25_Record_Student();
		$student->student_id = 765;
		$student->last_name = 'Smith';
		$student->first_name = 'John';
		$student->date_of_birth = '1990-01-01';
		$enroll->Student = $student;

		$course = new A25_Record_Course();
		$enroll->Course = $course;


		$court = new A25_Record_Court();
		$court->court_name = 'Supreme Pizza Court';
		$enroll->Court = $court;

		$lineitem = new test_unit_A25_Report_CollectedCourtSurcharges_FormatRowTest_OrderItem();
		$lineitem->unit_price = 23;
		$lineitem->date_paid = '2009-01-01';
		$enroll->Order = new A25_Record_Order();
		$enroll->Order->OrderItems[] = $lineitem;

		$expectedArray = array(
			'Date Paid' => $lineitem->date_paid,
			'Course Completed' => '2011-01-01',
			'Student ID' => $student->student_id,
			'First Name' => $student->first_name,
			'Last Name' => $student->last_name,
			'DOB'=> $student->date_of_birth,
			'Referring Court' => $court->court_name,
			'Amount' => '$'.$lineitem->faceValue()
		);
		$report = new test_unit_A25_Report_CollectedCourtSurcharges_FormatRowTest_CourtSurcharge();
		$this->assertEquals($expectedArray, $report->formatRow($lineitem));
	}
}
?>
