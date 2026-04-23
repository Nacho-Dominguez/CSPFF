<?php

class Controller_AfterLatePayment extends Controller
{
  public function executeTask()
  {
    $student = A25_CookieMonster::getStudentFromCookie();

    $edit = (bool)$_REQUEST['edit'];
		if ($edit)
			$readOnly = false;
		else
			$readOnly = true;

    $enroll = $student->getNewestEnrollment();
    if ($enroll->isComplete() && A25_DI::PlatformConfig()->hasCertificates)
      $payment = new A25_View_Student_PaymentAfterClass();
    else
      A25_DI::Redirector()->redirectBasedOnSiteRoot('/account', $_GET['mosmsg']);
    $payment->render($student, $readOnly);
  }
}
