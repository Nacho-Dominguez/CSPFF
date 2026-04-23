<?php

class A25_Report_Enrollment extends A25_Report
{
	protected $isLegacy = false;

    public function __construct($limit, $offset)
    {
        parent::__construct(null, $limit, $offset);

        $this->filters = array(
            new A25_Filter_CourseDate(),
            new A25_Filter_EnrollmentCourseId(),
            new A25_Filter_Zip(),
            new A25_Filter_EnrollmentStatus(),
            new A25_Filter_ReasonForEnrollment(),
            new A25_Filter_Court(),
            new A25_Filter_Location(),
            new A25_Filter_HearAboutType()
        );
    }

	protected function formatRow(A25_DoctrineRecord $enroll)
	{
		$return = A25_DI::PlatformConfig()->enrollmentReportFields($enroll);

		return self::fireAppendFormatRow($return, $enroll);
	}

	protected function query()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Course c')
			->innerJoin('e.Student s')
			->innerJoin('e.Status status')
      ->innerJoin('e.ReasonType r')
      ->leftJoin('e.Court court')
			->orderBy('s.last_name, s.first_name');

		return $q;
	}

	protected function name()
	{
		return 'Enrollment';
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
