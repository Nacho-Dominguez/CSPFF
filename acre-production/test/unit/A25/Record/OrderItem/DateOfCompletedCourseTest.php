<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_OrderItem_DateOfCompletedCourseTest extends
		test_Framework_UnitTestCase
{
	private $student;

	public function setUp()
	{
		parent::setUp();
		$this->student = new A25_Record_Student();
	}
	/**
	 * @test
	 */
	public function returnNull_whenNoCompletedEnrollment()
	{
		$lineitem = $this->lineitemFromEnrollment();

		$this->assertNull($lineitem->dateOfCompletedCourse());
	}
	/**
	 * @test
	 */
	public function returnCourseDate_whenEnrollmentOfLineItemIsCompleted()
	{
		$lineitem = $this->lineitemFromCompletedEnrollmentWithCourseDated('2010-01-01');

		$this->assertEquals('2010-01-01',$lineitem->dateOfCompletedCourse());
	}
	/**
	 * @test
	 */
	public function returnCourseDate_whenEnrollmentAfterLineItemIsCompleted()
	{
		$lineitem1 = $this->lineitemFromEnrollment();
    $enroll = $lineitem1->getEnrollment();
		$enroll->status_id = A25_Record_Enroll::statusId_canceled;
		$enroll->xref_id = 1;

		$lineitem2 = $this->lineitemFromCompletedEnrollmentWithCourseDated('2010-01-01');
    $enroll = $lineitem2->getEnrollment();
		$enroll->xref_id = 2;

		$this->assertEquals('2010-01-01',$lineitem1->dateOfCompletedCourse());
	}
	/**
	 * @test
	 */
	public function returnNull_whenEnrollmentBeforeLineItemIsCompleted()
	{
		$lineitem1 = $this->lineitemFromEnrollment();
    $enroll = $lineitem1->getEnrollment();
		$enroll->status_id = A25_Record_Enroll::statusId_canceled;
		$enroll->xref_id = 2;
		$lineitem2 = $this->lineitemFromCompletedEnrollmentWithCourseDated('2010-01-01');
    $enroll = $lineitem2->getEnrollment();
		$enroll->xref_id = 1;

		$this->assertNull($lineitem1->dateOfCompletedCourse());
	}

	
	private function lineitemFromEnrollment()
	{
		$enroll = new A25_Record_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_canceled;
		$enroll->Order = new A25_Record_Order();
		$lineitem = new A25_Record_OrderItem();
		$lineitem->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
		$enroll->Order->OrderItems[] = $lineitem;

		$this->student->Enrollments[] = $enroll;

		return $lineitem;
	}
	private function lineitemFromCompletedEnrollmentWithCourseDated($date)
	{
		$lineitem = $this->lineitemFromEnrollment();
		$course = new A25_Record_Course();
		$course->setCourseTime(strtotime($date));
    $enroll = $lineitem->getEnrollment();
		$enroll->Course = $course;
		$enroll->status_id = A25_Record_Enroll::statusId_completed;

		return $lineitem;
	}
}
?>
