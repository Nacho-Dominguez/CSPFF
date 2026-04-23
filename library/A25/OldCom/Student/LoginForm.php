<?php

class A25_OldCom_Student_LoginForm
{
	public static function run( $course_id, $nexttask, $Itemid ) {

		$course = false;
		
		if ($course_id) {
			$course = A25_Record_Course::retrieve( $course_id );
		}

		A25_OldCom_Student_LoginFormHtml::loginForm( $course, $course_id,
				$nexttask, $Itemid );
	}
}
?>
