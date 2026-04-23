<?php

class A25_Listing_LocationCourses extends A25_Listing_BrowseCourses
{
	private $location_id;

	public function __construct($location_id, $offset)
	{
		parent::__construct(null, null, $offset);

		$this->location_id = $location_id;
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
				->andWhere('c.location_id = ?', $this->location_id)
				->orderBy('c.course_start_date');

		return $q;
	}

	protected function headingTop()
	{
		?>
		<div style="float: right;">
			<a href="<?php echo A25_Link::withoutSef(
				'find-a-course?zip=' . $location->zip) ?>">See classes at other nearby locations</a>
		</div>
		<?php
	}
}
