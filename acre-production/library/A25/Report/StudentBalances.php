<?php

class A25_Report_StudentBalances extends A25_Report
{
	protected $isLegacy = false;
	
	public function __construct($limit, $offset)
	{
		parent::__construct(null, $limit, $offset);
		
		$this->filters = array(
      new A25_Filter_LastPaymentDate(),
		);
	}
	
	protected function name()
	{
		return "Unclaimed Balances";
	}
	
	protected function formatRow(A25_DoctrineRecord $student)
	{
    return array(
      'Student ID' => A25_RecordLinks::studentLink($student->student_id),
      'Account Balance' => $student['calc_balance'],
      'Last Payment Date (balance will expire 1 year after this date)' => $student['calc_last_payment_date']
    );
	}
	
	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_Student s')
      ->where('s.calc_balance < 0');
	}
}
