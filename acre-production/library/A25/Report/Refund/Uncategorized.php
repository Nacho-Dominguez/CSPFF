<?php

class A25_Report_Refund_Uncategorized extends A25_Report
{
	protected function name()
	{
		return "Uncategorized Refunds";
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

	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_Pay p')
			->innerJoin('p.Order o')
			->innerJoin('o.Enrollment e')
			->where('p.refund_type_id IS NULL')
			->andWhere('p.amount < 0')
			->andWhere('p.created > ?', date('Y-m-d h:i:s', $this->filter->from))
			->andWhere('p.created < ?', date('Y-m-d h:i:s', $this->filter->to));
	}

	public function getTotal()
	{
		$q = $this->query()->select('SUM(p.amount) as total');
		$payments = $q->fetchOne();
		if ($payments->total)
			return -$payments->total;
		return 0;
	}
}
