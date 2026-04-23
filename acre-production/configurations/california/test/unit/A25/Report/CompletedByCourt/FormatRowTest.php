<?php
class test_unit_FormatRowTest_CompletedByCourt
		extends A25_Report_CompletedByCourt
{
	public function __construct()
	{
	}
	public function formatRow(A25_DoctrineRecord $enroll)
	{
		return parent::formatRow($enroll);
	}
}

class test_unit_A25_Report_CompletedByCourt_FormatRowTest extends
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
		$enroll->Student = $student;

		$course = new A25_Record_Course();
		$course->course_start_date = '2010-01-01 12:00:00';
		$enroll->Course = $course;

		$court = new A25_Record_Court();
		$court->court_id = 2;
		$court->court_name = 'court name';
		$court->collect_docket_number = true;
		$enroll->assignCourt($court);

		$enroll->date_completed = '2010-01-02 12:00:00';
		$enroll->court_docket_number = 'N1234';


		$student_id_link = '<a href="' .
			A25_Link::to(
				'/administrator/index2.php?option=com_student&task=viewA&id='
				. $student->student_id)
			. '">' . $student->student_id . '</a>';

		$expectedArray = array(
			'Student ID' => $student_id_link,
			'Last Name' => $student->last_name,
			'First Name' => $student->first_name,
			'Court Name' => $court->court_name,
			'Course Date' => $enroll->Course->date(),
			'Date Completed and Paid' => $enroll->date_completed,
			'Docket Number' => $enroll->court_docket_number
		);
		$report = new test_unit_FormatRowTest_CompletedByCourt();
		$this->assertEquals($expectedArray, $report->formatRow($enroll));
	}
}
?>
