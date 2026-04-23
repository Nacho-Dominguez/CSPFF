<?php
class test_unit_FormatRowTest_Court
		extends A25_Report_Court
{
	public function __construct()
	{
	}
	public function formatRow(A25_DoctrineRecord $court)
	{
		return parent::formatRow($court);
	}
}

class test_unit_A25_Report_Court_FormatRowTest extends
		test_Framework_UnitTestCase
{
	private $court;
	/**
	 * @test
	 */
	public function returnsExpectedArray()
	{
		$this->court = new A25_Record_Court();
		$this->court->court_id = 5;
		$this->court->court_name = 'A Court Name';
		$this->court->city = 'Somewhere';
		$this->court->Zip = new A25_Record_Zip();
		$this->court->Zip->county = 'Jefferson';
		$this->court->state = 'CO';

		$paidItem = $this->createLineItemWithEnrollmentWithStatus(
				A25_Record_Enroll::statusId_completed, 'M');
		$paidItem->date_paid = '2010-07-19';

		$waivedItem = $this->createLineItemWithEnrollmentWithStatus(
				A25_Record_Enroll::statusId_canceled, 'M');
		$waivedItem->date_paid = '2010-07-19';
		$waivedItem->waive();

		$unpaidItem = $this->createLineItemWithEnrollmentWithStatus(
				A25_Record_Enroll::statusId_registered, 'F');


		$expectedArray = array(
			'Court ID' => 5,
			'Court Name' => 'A Court Name',
			'City' => 'Somewhere',
			'County' => 'Jefferson',
			'State' => 'CO',
			'# Reg' => '2',
			'# Com' => '1',
			'# M' => '1',
			'# F' => '1',
			'% M' => '50.0%',
			'% F' => '50.0%',
			'Revenue' => '$30'
		);

		$report = new test_unit_FormatRowTest_Court();
		$this->assertEquals($expectedArray, $report->formatRow($this->court));
	}

	private function createLineItemWithEnrollmentWithStatus($status,$gender)
	{
		$enroll = new A25_Record_Enroll();
		$enroll->status_id = $status;
		$enroll->Student = new A25_Record_Student();
		$enroll->Student->gender = $gender;
		$enroll->Order = new A25_Record_Order();
		$item = $enroll->Order->createLineItem(A25_Record_OrderItemType::typeId_CourseFee,30);
		$this->court->Enrollments[] = $enroll;

		return $item;
	}
}