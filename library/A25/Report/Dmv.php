<?php

class A25_Report_Dmv extends A25_Report
{
	protected function formatRow(A25_DoctrineRecord $enroll)
	{
		if ($enroll->Course->relatedIsDefined('Instructor'))
			$control = $enroll->Course->Instructor->control;
		else
			$control = null;
		return array(
      'Name' => $enroll->Student->firstLastName(),
      // Add empty columns to facilitate copy/paste
      '0' => '',
      'DOB' => date('n/j/Y', strtotime($enroll->Student->date_of_birth)),
			'Course Date' => $enroll->Course->formattedDate('course_start_date', 'j'),
      '1' => '',
      '2' => '',
      '3' => '',
      '4' => '',
      'Instructor DOR #' => $control
		);
	}
	
	protected function query()
	{
		return Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Course c')
			->innerJoin('e.Student s')
      ->leftJoin('e.Status status')
			->leftJoin('c.Instructor i')
			->where('reason_id=?',A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit)
			->andCourseStartsWithin($this->filter)
			->andWhereIn('status_id',array(A25_Record_Enroll::statusId_completed,
						A25_Record_Enroll::statusId_pending));
	}

	protected function name()
	{
		return 'DMV';
	}
}
