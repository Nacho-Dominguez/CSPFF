<?php

class A25_Report_UpcomingCourseRevenue extends A25_Report
{
	protected $isLegacy = false;
	
	public function __construct($limit, $offset)
	{
		parent::__construct(null, $limit, $offset);
		
		$this->filters = array(
		);
	}
	
	protected function name()
	{
		return "Fees paid for upcoming courses";
	}
	
	protected function formatRow(A25_DoctrineRecord $orderItem)
	{
    $formatter = new RowFormatter4UpcomingCourseRevenueReport($orderItem);
    return $formatter->formatRow();
	}
	
	protected function query()
	{
		return A25_Query::create()
			->from('A25_Record_OrderItem i')
      ->innerJoin('i.Order o')
      ->innerJoin('o.Enrollment e')
      ->innerJoin('e.Course c')
      ->andFeeIsDeferredRevenueForUpcomingCourse();
	}
}

class RowFormatter4UpcomingCourseRevenueReport extends A25_RowFormatter4FeeReports
{
	public function formatRow()
	{
		return array(
			'Type' => $this->orderItem->getTypeName(),
			'Student ID' => $this->studentLink(),
			'Enrollment ID' => $this->enrollLink(),
			'Course ID' => $this->courseLink(),
			'Amount' => $this->orderItem->chargeAmount(),
      'Date Paid' => $this->orderItem->date_paid,
      'Course Date' => date('Y-m-d', strtotime($this->orderItem->courseDatetime()))
		);
	}
}
