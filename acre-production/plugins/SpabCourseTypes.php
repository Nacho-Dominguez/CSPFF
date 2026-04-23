<?php
class A25_Plugin_SpabCourseTypes implements
    A25_ListenerI_ActionColumn
{
	public function actionColumn(A25_Record_Course $course)
	{
		$text = $this->actionColumnMessageText($course);
		if ($text)
			return $this->actionColumnMessage($text);
	}

	protected function actionColumnMessage($text)
	{
		return '<div style="font-size: 10px; color: #770000;">(' . $text
				. ')</div>';
	}

	private function actionColumnMessageText(A25_Record_Course $course)
	{
		if ($course->course_type_id == 1) // Original course
			return 'Original Training';
		if ($course->course_type_id == 2) // 1st renewal
			return 'Renewal Training Part 1';
		if ($course->course_type_id == 4) // 2nd renewal
			return 'Renewal Training Part 2';
	}
}
