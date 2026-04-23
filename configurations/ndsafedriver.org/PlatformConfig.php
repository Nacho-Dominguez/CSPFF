<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState {

	const AUTHORIZE_NET_LOGIN = '4xmaS72Jh3V';
	const AUTHORIZE_NET_TRAN_KEY = '98VN2c6vY5Gmy4cE';
	const STATE_NAME = 'North Dakota';
  const STATE_ABBREV = 'nd';
	const displayPriceOnCertificate = false;
	const certPrinter = 'cert_pdf.php';
	const schoolType = 'School';

	const phoneNumber = '(701) 223-6372';
	const faxNumber = '(701) 223-0087';
	const businessHours = 'Monday-Friday, 8:30am-4:30pm, Central Time';
	const mailingAddressName = 'Alive At 25';

	const messageSenderId = 440;
    const noShowsBeforeNoShowFee = 1;

	static public function paymentAddressHtml()
	{
		return self::administrativeAddressHtml();
	}
	static public function administrativeAddressHtml()
	{
		return self::mailingAddressName . '<br/>'
			   . '1640 Burnt Boat Dr.<br/>'
			   . 'Bismarck, ND 58503';
	}
	const agency = 'North Dakota Safety Council';

  public function certPdfSettings()
  {
    return new A25_CertPdfSettings_New();
  }

  public function findACourseComments()
  {
    return
'<p>
Classes occurring within 7 days require immediate payment with credit card in
order to enroll.  These are marked with an \'<span class="asterisk">*</span>\'.
</p>
<p>
Classes outside of 7 days have the option to be paid with check, money order, or
credit card.
</p>';
  }

  public function tuitionDetails($tuition)
  {
    return '<p>Tuition is ' . $tuition . ' for students taking the course voluntarily.</p>
      <p>Tuition is $' . PlatformConfig::defaultCourtFee . ' for court-ordered students</p>';
  }

  public function slowPaymentCertificateText(A25_Record_Course $course)
  {
    return 'Please make sure payment arrives before '
        . date('g:i a \o\n l, F j', strtotime($course->course_start_date . ' -'
        . $course->getSetting('late_fee_deadline') . ' hours'))
        . ' to
avoid a late payment fee of $' . $course->getLateFee()
        . '.  To ensure your certificate of
completion will be pre-printed and available at the class we must receive
payment prior to noon one week prior to the class date.  ';
  }
  public function includeOnCheckText(A25_Record_Student $student)
  {
    return '<br/><br/>Please include the student\'s name, '
        . PlatformConfig::courseTitleHtml() . ' student ID number (#'
        . $student->student_id . ') and the city and date of class attending '
        . 'with your payment.';
  }
  public function cancellationPolicyForEmail()
  {
    return 'If you are unable to attend this class, please <a href="'
        . PlatformConfig::accountUrlDirect()
        . '">cancel or reschedule</a> your class as soon as possible.
<br/><br/>
Cancellation Policy:  If you are unable to attend a training session, you must
cancel at least three business days prior to the course for a full refund.
Late or un-cancelled registrations are non-refundable.  No one will be allowed
into the class once class is in session.';
  }
}
