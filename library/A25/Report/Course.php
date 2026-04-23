<?php

class A25_Report_Course extends A25_Report
{
	protected $isLegacy = false;

	public function __construct($limit, $offset)
	{
		parent::__construct(null, $limit, $offset);
		
		$this->filters = array(
			new A25_Filter_CourseDate(),
			new A25_Filter_CourseStatus(),
			new A25_Filter_CourseType(),
			new A25_Filter_Location()
		);
	}

	protected function formatRow(A25_DoctrineRecord $course)
	{
		$link = '<a href="' . A25_Link::to(
					'/administrator/index2.php?option=com_course&task=viewA&id='
					. $course->course_id)
				. '">' . $course->course_id . '</a>';
		
		$linkEnrollments = '<a href="' . A25_Link::to(
				'/administrator/index2.php?option=com_stats&task=enrollment&course_id='
					. $course->course_id) . '">' . count($course->getSeatsTaken()) . '</a>';
		
		$return = array(
			'Course ID' => $link,
			'Date/Time' => $course->getFormattedDateTime(),
			'Type' => $course->typeName(),
			'Status' => $course->getStatus(),
			'Location' => $course->getLocationName(),
			'Enrollments' => $linkEnrollments,
			'# Instructors' => $course->getNumberOfInstructors(),
			/*'Profit' => $course->getProfit()*/
		);
		return self::fireAppendCourseFormatRow($return, $course);
	}
	
	protected function query()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Course c')
      /*->leftJoin('c.Type t')
			->leftJoin('c.Enrollments e')
			->leftJoin('e.Order o')
			->leftJoin('o.Payments p')
			->leftJoin('o.OrderItems i')
      ->leftJoin('c.Location l')*/
			->orderBy('c.course_start_date');

		return $q;
	}

	protected function name()
	{
		return 'Course';
	}

	private static function fireAppendCourseFormatRow(array $formatRow,
			A25_Record_Course $course)
	{
		foreach (A25_ListenerManager::all() as $listener)
		{
			if ($listener instanceof A25_ListenerI_AppendCourseReportFormatRow)
			{
				$formatRow = $listener->appendCourseReportFormatRow($formatRow,
						$course);
			}
		}
		return $formatRow;
	}
}
