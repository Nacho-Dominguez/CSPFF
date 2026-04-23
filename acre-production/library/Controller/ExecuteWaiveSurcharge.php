<?php

class Controller_ExecuteWaiveSurcharge extends Controller
{
  public function executeTask()
  {
    $id = intval($_REQUEST['item_id']);
    $type = intval($_REQUEST['waive']);
    $fee = A25_Record_OrderItem::retrieve($id);
    $fee->waive($type);
    $this->sendAdminEmailAboutWaive($fee);
    $fee->save();
    
    $student = $fee->Order->Enrollment->Student;
    $student->updateOrdersAndEnrollmentsAfterPayment();
    
    A25_DI::Redirector()->redirectBasedOnSiteRoot('/account');
  }

	private function sendAdminEmailAboutWaive($fee) {
    $order = $fee->Order;
    $enroll = $order->Enrollment;
    $student = $enroll->Student;
    $course = $enroll->Course;
		$subject = A25_EmailContent::wrapSubject('Notification of student waived surcharge');
		$body = 'Student information:<br />'
				. $student->last_name . ', ' . $student->first_name
				. ' ' . $student->student_id . '<br />'
				. 'Enrollment information:<br />'
				. 'Enroll ID: ' . $enroll->xref_id . '<br />'
				. 'Location: ' . $course->getLocationName() . ' on '
				. $course->getFormattedDateTime() . ' (course ID: '
				. $course->course_id . ')<br />'
				. 'Court: ' . $enroll->Court->court_name . ' ' . $enroll->Court->court_id . '<br />';
		A25_DI::Mailer()->mail(ServerConfig::adminEmailAddress, $subject, $body);
	}
}
