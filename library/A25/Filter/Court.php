<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_Court extends A25_Filter
{
	protected $court_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->court_ids) {
			$q->andWhereIn('e.court_id', $this->court_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Court';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('court_ids', 'A25_Record_Court',
				'court_name');
	}
}