<?php

class A25_Report_Income extends A25_Report
{
	protected $isLegacy = false;
	
	public function __construct($limit, $offset)
	{
		parent::__construct(null, $limit, $offset);
		
		$this->filters = array(
			new A25_Filter_AccrualDate(),
      new A25_Filter_AccountsReceivable(),
      new A25_Filter_FeeType(),
		);
	}
	
	protected function name()
	{
		return "Income";
	}
	
	protected function formatRow(A25_DoctrineRecord $orderItem)
	{
    $formatter = new A25_RowFormatter4IncomeReport($orderItem);
    return $formatter->formatRow();
	}
	
	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_OrderItem i')
      ->innerJoin('i.Order o')
      ->innerJoin('o.Enrollment e')
      ->innerJoin('e.Course c')
      ->where('i.calc_accrual_date IS NOT NULL')
      ->orderBy('i.calc_accrual_date');
	}
}
