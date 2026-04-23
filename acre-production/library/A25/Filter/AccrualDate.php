<?php

class A25_Filter_AccrualDate extends A25_Filter_DateRange
{
	public $accrual_date_from;
	public $accrual_date_to;

	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->accrual_date_from) {
			$q->andWhere('i.calc_accrual_date >= ?', date('Y-m-d',
					strtotime($this->accrual_date_from)));
		}
		if ($this->accrual_date_to) {
			$q->andWhere('i.calc_accrual_date < ?',
          A25_Functions::addADay($this->accrual_date_to));
		}
    
    return $q;
	}
	
	protected function title()
	{
		return 'Accrual Date';
	}
	
	protected function field()
	{
    return $this->smartField('accrual_date_from', 'accrual_date_to');
	}
}