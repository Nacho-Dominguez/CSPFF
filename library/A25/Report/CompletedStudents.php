<?php

/**
 * The data on this report can be obtained by using filters on the Enrollment
 * Report.  The only reason we have kept this report is that California has
 * customized it to include their Court Docket #.  Eventually, the subclass of
 * this report which accomplished that customization should be refactored into a
 * Plugin and applied to the Enrollment Report instead, so that we can delete
 * this report altogether.
 */
class A25_Report_CompletedStudents extends A25_Report
{
	protected function formatRow(A25_DoctrineRecord $enroll)
	{
		$student_id_link = '<a href="' .
			A25_Link::to(
				'/administrator/index2.php?option=com_student&task=viewA&id='
				. $enroll->student_id)
			. '">' . $enroll->student_id . '</a>';
		return array(
			'Student ID' => $student_id_link,
			'Last Name' => $enroll->Student->last_name,
			'First Name' => $enroll->Student->first_name,
			'Email' => $enroll->Student->email,
			'Course Date' => $enroll->Course->date(),
			'Date Completed and Paid' => $enroll->date_completed
		);
	}

	protected function query()
	{
		$q = Doctrine_Query::create()
			->from('A25_Record_Enroll e')
			->innerJoin('e.Student s')
			->innerJoin('e.Course c')
			->where('e.status_id = ?', A25_Record_Enroll::statusId_completed)
			->andWhere("e.date_completed >= ?", date('Y-m-d',$this->filter->from))
			->andWhere("e.date_completed <= ?", date('Y-m-d',$this->filter->to))
			->orderBy('e.date_completed');

		return $q;
	}

	protected function name()
	{
		return 'Completed Students';
	}
}
