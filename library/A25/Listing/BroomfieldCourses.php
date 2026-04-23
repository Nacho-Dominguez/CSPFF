<?php

class A25_Listing_BroomfieldCourses extends A25_Listing_BrowseCourses
{
    // This is a custom listing for locations that Broomfield Municipal Court holds classes

	public function __construct($offset)
	{
		parent::__construct(null, null, $offset);
	}

	/**
	 * @todo-soon - make test for query
	 */
	protected function query()
	{
		$q = Doctrine_Query::create()
				->from('A25_Record_Course c')
				->innerJoin('c.Location l')
				->leftJoin('c.Enrollments e')
				->where('c.course_start_date >= ?', date('Y-m-d') . ' 00:00:00')
				->andWhereIn('c.status_id', A25_Record_Course::$activeStatuses)
				->andWhere('c.published = ?', 1)
				->andWhereIn('c.location_id', array(503, 504, 505))
				->orderBy('c.course_start_date');

		return $q;
	}

	protected function headingTop()
	{
        // Don't show option to see other courses
	}
}
