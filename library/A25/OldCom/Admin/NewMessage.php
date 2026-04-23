<?php


class A25_OldCom_Admin_NewMessage
{
	function run( $course_id, $my, $option='com_course') {

		$row = A25_Record_Course::retrieve( $course_id );

		$lists = array();

		A25_OldCom_Admin_NewMessageHtml::newMessage($row, $lists, $option);
	}
}
?>
