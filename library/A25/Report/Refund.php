<?php
class A25_Report_Refund extends A25_Report
{
	public function __construct($filter, $limit, $offset)
	{
		parent::__construct($filter, $limit, $offset);
		
		$this->filters = array(
			new A25_Filter_RefundType()
		);
	}
	protected function name()
	{
		return 'Refund';
	}
	protected function formatRow(A25_DoctrineRecord $pay)
	{
		$student_link = A25_RecordLinks::linkToTask('option=com_student&task=viewA&id=',
			 $pay->getStudent()->student_id);
		$enroll_link = A25_RecordLinks::linkToTask(
			'option=com_student&task=enrollview&xref_id=',
			$pay->Order->xref_id);
		$course_link = A25_RecordLinks::linkToTask('option=com_course&task=viewA&id=',
			$pay->getCourse()->course_id);
		if ($pay->relatedIsDefined('RefundType'))
			$refund_type = $pay->RefundType->getName();
		
		return array(
			'Refund ID' => $pay->pay_id,
			'Type' => $refund_type,
			'Student ID' => $student_link,
			'Enrollment ID' => $enroll_link,
			'Course ID' => $course_link,
			'Amount' => '$'.-$pay->amount,
			'Date Originally Collected' => $pay->refund_date_originally_collected,
			'Notes' => $pay->notes
		);
	}

	/**
	 * @return collection of A25_Record_Pay
   * Even though the official relationship is Order to Enrollment, using
   * p.Enrollment here keeps the report from querying the database for each
   * individual enrollment
	 */
	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_Pay p')
			->innerJoin('p.Order o')
			->innerJoin('p.Enrollment e')
      ->innerJoin('p.Student s')
      ->innerJoin('e.Course c')
      ->innerJoin('p.RefundType t')
			->andWhere('p.created > ?', date('Y-m-d h:i:s', $this->filter->from))
			->andWhere('p.created < ?', date('Y-m-d h:i:s', $this->filter->to))
      ->andWhere('amount < 0');
	}
}
