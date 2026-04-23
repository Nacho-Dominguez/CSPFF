<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_Location extends A25_Filter
{
	protected $location_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->location_ids) {
			$q->andWhereIn('c.location_id', $this->location_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Location';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('location_ids', 'A25_Record_Location',
				'location_name');
	}
}