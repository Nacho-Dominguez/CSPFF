<?php

class A25_OldCom_Student_CancelEnrollment
{
	public static function run($xref_id) {

		$enroll = A25_Record_Enroll::retrieve( $xref_id );
		return $enroll->cancelEnrollment();
	}
}
?>
