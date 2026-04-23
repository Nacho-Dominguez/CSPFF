<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_EnrollmentStatus extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $status_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->status_ids) {
			$q->andWhereIn('e.status_id', $this->status_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Enrollment Status';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('status_ids', 'A25_Record_EnrollStatus');
	}
}