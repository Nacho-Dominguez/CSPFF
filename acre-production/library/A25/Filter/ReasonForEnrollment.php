<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_ReasonForEnrollment extends A25_Filter
{
	protected $reason_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->reason_ids) {
			$q->andWhereIn('e.reason_id', $this->reason_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Reason for Enrollment';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('reason_ids', 'A25_Record_ReasonType',
				'reason_name');
	}
}