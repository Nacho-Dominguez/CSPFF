<?php

/**
 * The data on this report can be obtained by using filters on the Enrollment
 * Report.  The only reason we have kept this report is that California has
 * customized it to include their Court Docket #.  Eventually, the subclass of
 * this report which accomplished that customization should be refactored into a
 * Plugin and applied to the Enrollment Report instead, so that we can delete
 * this report altogether.
 */
class A25_Report_CompletedByCourt extends A25_Report
{

	public function __construct(A25_ReportFilter $filter, $limit, $offset)
	{
		parent::__construct($filter, $limit, $offset);

		$this->filters = array(new A25_Filter_Court());

	}
	protected function formatRow(A25_DoctrineRecord $enroll)
	{
		$student_id_link = '<a href="' .
			A25_Link::to(
				'/administrator/index2.php?option=com_student&task=viewA&id='
				. $enroll->student_id)
			. '">' . $enroll->student_id . '</a>';
		$return = array(
			'Student ID' => $student_id_link,
			'Last Name' => $enroll->Student->last_name,
			'First Name' => $enroll->Student->first_name,
			'Court Name' => $enroll->courtName(),
			'Course Date' => $enroll->Course->date(),
			'Date Completed and Paid' => $enroll->date_completed,
            'Location' => $enroll->Location->location_name
		);
		
		return self::fireAppendFormatRow($return, $enroll);
	}

	protected function query()
	{
		$q = $this->queryWithoutCourtFilter()
			->andWhere("e.date_completed >= ?", date('Y-m-d',$this->filter->from))
			->andWhere("e.date_completed <= ?", date('Y-m-d',$this->filter->to));

		return $q;
	}

	protected function queryWithoutCourtFilter()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Student s')
			->innerJoin('e.Course c')
			->leftJoin('e.Court ct')
			->where('e.status_id = ?', A25_Record_Enroll::statusId_completed)
			->andwhereIn('e.reason_id',A25_Record_ReasonType::legalMatterList())
			->orderBy('e.date_completed');

		return $q;
	}

	protected function name()
	{
		return 'Completed Students By Court';
	}
	
	private static function fireAppendFormatRow(array $formatRow,
			A25_Record_Enroll $enroll)
	{
		foreach (A25_ListenerManager::all() as $listener)
		{
			if ($listener instanceof A25_ListenerI_AppendEnrollmentReportFormatRow)
			{
				$formatRow = $listener->appendEnrollmentReportFormatRow($formatRow,
						$enroll);
			}
		}
		return $formatRow;
	}
}
