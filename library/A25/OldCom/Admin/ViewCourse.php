<?php

class A25_OldCom_Admin_ViewCourse
{
	public function run($course_id, $option='com_course')
	{
		$row = A25_Record_Course::retrieve( $course_id);
		A25_OldCom_Admin_ViewCourseHtml::viewCourse($row, $option);
	}
}
?>
