<?php

class A25_Filter_LastPaymentDate extends A25_Filter_DateRange
{
	public $last_payment_date_from;
	public $last_payment_date_to;

	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->last_payment_date_from) {
			$q->andWhere('s.calc_last_payment_date >= ?', date('Y-m-d',
					strtotime($this->last_payment_date_from)));
		}
		if ($this->last_payment_date_to) {
			$q->andWhere('s.calc_last_payment_date < ?',
          A25_Functions::addADay($this->last_payment_date_to));
		}
    
    return $q;
	}
	
	protected function title()
	{
		return 'Last Payment Date';
	}
	
	protected function field()
	{
    return $this->smartField('last_payment_date_from', 'last_payment_date_to');
	}
}