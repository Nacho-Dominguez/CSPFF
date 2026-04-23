<?php

class A25_OldCom_Admin_ViewRoster
{
	function run( $course_id, $option='com_course' ) {

		$course = A25_Record_Course::retrieve( $course_id );

		$lists = array();

		A25_OldCom_Admin_ViewRosterHtml::viewRoster( $course, $lists, $option );
	}
}
?>
