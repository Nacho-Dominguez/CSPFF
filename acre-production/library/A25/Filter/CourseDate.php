<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_CourseDate extends A25_Filter_DateRange
{
	protected $course_date_from;
	protected $course_date_to;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->course_date_from) {
			$q->andWhere('c.course_start_date >= ?', date('Y-m-d',
					strtotime($this->course_date_from)));
		}
		if ($this->course_date_to) {
			$q->andWhere('c.course_start_date < ?',
          A25_Functions::addADay($this->course_date_to));
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Course Date';
	}
	
	protected function field()
	{
    return $this->smartField('course_date_from', 'course_date_to');
	}
}