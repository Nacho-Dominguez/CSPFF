<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_DonationDate extends A25_Filter_DateRange
{
	protected $donation_date_from;
	protected $donation_date_to;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->donation_date_from) {
			$q->andWhere('d.created >= ?', date('Y-m-d',
					strtotime($this->donation_date_from)));
		}
		if ($this->donation_date_to) {
			$q->andWhere('d.created < ?',
          A25_Functions::addADay($this->donation_date_to));
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Donation Date';
	}
	
	protected function field()
	{
    return $this->smartField('donation_date_from', 'donation_date_to');
	}
}