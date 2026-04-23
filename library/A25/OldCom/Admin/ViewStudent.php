<?php


class A25_OldCom_Admin_ViewStudent {
    
	/**
	 * View information for an individual student
	 *
	 * @param integer $student_id
	 * @param  string $option
	 * @return void
	 */
	function viewStudent( $student_id, $optionForReturnButton='com_student' )
	{
		$studentRecord = A25_Record_Student::retrieve( $student_id );
		$studentRecord->getStringValues();

		A25_OldCom_Admin_ViewStudentHtml::viewStudent( $studentRecord, $optionForReturnButton );
	}
}
?>
