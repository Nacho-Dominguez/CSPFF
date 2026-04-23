<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_Instructor extends A25_Filter
{
	protected $instructor_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->instructor_ids) {
			$q->andWhere('c.instructor_id IN (' . implode(',', $this->instructor_ids)
          . ') OR c.instructor_2_id IN (' . implode(',', $this->instructor_ids)
          . ')');
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Instructor';
	}
	
	protected function field()
	{
    $q = Doctrine_Query::create()->select()
					->from('A25_Record_User');
		return $this->generateMultiSelect('instructor_ids', 'A25_Record_User',
				'name', $q);
	}
}