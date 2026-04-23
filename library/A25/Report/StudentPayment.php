<?php

class A25_Report_StudentPayment extends A25_Report
{
	private $student_id;
	public function __construct($student_id)
	{
		$this->student_id = $student_id;
		$limit = 25;
		$offset = 0;
		parent::__construct(new A25_ReportFilter(), $limit, $offset);
	}

	protected function formatRow(A25_DoctrineRecord $pay)
	{
		$pay_id_link = '<a href="' .
			A25_Link::to(
					'/administrator/index2.php?option=com_pay&task=viewA&id='
					. $pay->pay_id)
			. '">' . $pay->pay_id . '</a>';

		$student = $pay->Student;
		$name = $student->firstLastName();
		$dob = ($student->date_of_birth ?
				date('m/d/Y', strtotime($student->date_of_birth)) : '');
		$address = $student->fullAddress()
				. '<br />';

		return array(
			'ID' => $pay_id_link,
			'Name' => $name,
			'DOB' => $dob,
			'Address' => $address,
			'Phone' => $student->home_phone,
			'Amount' => ($pay->amount ? '$' . $pay->amount : ''),
			'Check #' => $pay->check_number,
			'Paid Date' => ($pay->created ? date('m/d/Y', strtotime($pay->created))  : ''),
			'Paid Method' => $pay->getPaymentTypeName(),
			'Transaction ID' => $pay->cc_trans_id,
			'Paid By' => ($pay->paid_by_name ? $pay->paid_by_name : ''),
			'Taken By' => ($pay->created_by > 0 ? $pay->CreatedBy->name : ''),
			'Notes' => $pay->notes
		);
	}
	
	protected function query()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Pay p')
			->innerJoin('p.Student s')
			->leftJoin('p.CreatedBy u')
			->where('s.student_id = ?', $this->student_id)
      ->orderBy('p.created');

		return $q;
	}

	protected function name()
	{
		return 'List Student Payments';
	}
	
	protected function filters()
	{
	}
}