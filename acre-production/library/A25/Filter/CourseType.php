<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_CourseType extends A25_Filter
{
	/**
	 * @var array
	 */
	protected $course_type_ids;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->course_type_ids) {
			$q->andWhereIn('c.course_type_id', $this->course_type_ids);
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Type';
	}
	
	protected function field()
	{
		return $this->generateMultiSelect('course_type_ids', 'A25_Record_CourseType');
	}
}