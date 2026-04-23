<?php

require_once(dirname(__FILE__) . '/admin.student.html.php');

$link = 'index2.php?option=com_student&task=studentForm&id=' . $row->student_id;
HTML_student::quickiconButton( $link, 'addedit.png', 'Edit Student' );

$link = 'index2.php?option=com_student&task=enrollform&id=' . $row->student_id;
HTML_student::quickiconButton( $link, 'addusers.png', 'Enroll in Course' );

$link = 'index2.php?option=com_student&task=noteform&id=' . $row->student_id;
HTML_student::quickiconButton( $link, 'addedit.png', 'Add Note' );

$link = 'index2.php?option=com_pay&task=liststudentpayments&student_id=' . $row->student_id;
HTML_student::quickiconButton( $link, 'query.png', 'View Payments/Credits' );

if (A25_DI::User()->isAdminOrHigher()) {
	$student = A25_Record_Student::retrieve($row->student_id);
	if (count($student->Payments) > 0) {
		$link = 'index2.php?option=com_pay&task=refundform&student_id=' . $row->student_id;
		HTML_student::quickiconButton( $link, 'browser.png', 'Refund' );
	}
}