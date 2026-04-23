<?php

class A25_OldCom_Admin_SaveEnroll
{
	function run( $course_id, A25_Redirector $redirector=null ) {

		$msg = '';

		$count = 0;
		foreach ($_POST['status'] as $xref_id => $status_id ) {
			$enroll = A25_Record_Enroll::retrieve( $xref_id );
      if (!$enroll)
        throw new Exception('Tried to load enrollment #' . $xref_id . ' but it does not exist');

      $currentStatus = $enroll->status_id;

			// Check to see if this student's status is already complete
			if ($status_id == A25_Record_Enroll::statusId_completed) {
				// Set status to pending if student has not yet paid
				if ($currentStatus == A25_Record_Enroll::statusId_registered
            || $currentStatus == A25_Record_Enroll::statusId_pending) {
					$status_id = A25_Record_Enroll::statusId_pending;
				}
			}

      if ($status_id != $enroll->status_id || $status_id == A25_Record_Enroll::statusId_noShow) { // Work around bug where no shows are sometimes not being charged.  The no show code has its own protections from double charges.
        $enroll->kick_out_date = null;
        $enroll->status_id = $status_id;
        $enroll->saveAfterApplyingBusinessRules();

        // While it may seem like there should be a call to
        // $enroll->Student->updateOrdersAndEnrollmentsAfterPayment()
        // here, it causes problems because there are actually other
        // uses for the "Pending" status besides just "completed but not
        // paid".  By not calling it here, we allow Instructors to use
        // "Pending" in those other ways.  If we ever add other
        // Enrollment statuses so that "Pending" isn't split with
        // multiple meanings, then we should add a call to
        // updateOrdersAndEnrollmentsAfterPayment() here.

        if ($enroll->status_id == A25_Record_Enroll::statusId_completed) {
          $count++;
        }
      }
		}

    if ($_POST['comments']) {
      $comments = new JosCourseComments();
      $comments->course_id = $course_id;
      $comments->comments = $_POST['comments'];
      $comments->save();
    }

    if ($_POST['close_course'] == 1)
      payCourse($course_id);

		$msg .= 'Successfully Updated Enrollment.';
		if($count){
			$msg .= " Sent $count course completion e-mails to students.";
		}

		if (!$redirector)
			$redirector = new A25_Redirector();
		$redirector->redirect( 'index2.php?option=com_course&task=viewA&id='. $course_id, $msg );
	}
}
