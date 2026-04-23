<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_CourseStatus extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $course_status_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->course_status_ids) {
			$q->andWhereIn('c.status_id', $this->course_status_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Status';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('course_status_ids', 'A25_Record_CourseStatus');
	}
}