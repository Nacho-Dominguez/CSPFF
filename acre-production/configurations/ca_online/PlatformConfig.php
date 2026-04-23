<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '54Y3Bswj7Cc';
    const AUTHORIZE_NET_TRAN_KEY = '6u23Pqp7NgQ476e9';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = false;
    public $requireEmail = true;

  // This setting is necessary so that the yellow 'selected state' box is
  // hidden
    const isAState = false;
    const STATE_NAME = "California";
    const STATE_ABBREV = 'ca';
    
    const agency = 'California Safe Driver';
    const phoneNumber = '1-877-525-DRIVE (3748)';
    const faxNumber = null;
    const businessHours = 'Monday-Friday 9am-5pm Pacific Time';

    public $courseIsOnline = true;
    public $affid = 'A0056534';

    public $kickOutAfterDeadline = 'never';

    public function colorScheme()
    {
        return new A25_ColorScheme_SPABOrange();
    }

    const courseTitle = 'DDC Online';
    const courseTitleFull = 'DDC Online';
    const siteTitle = 'DDC Online';

    const defaultCourtFee = 79;

    const minAge = 14;
    const maxAge = 9999;

    const messageSenderId = 63;

    public static function paymentAddressHtml()
    {
        return PlatformConfig::administrativeAddressHtml();
    }
    public static function administrativeAddressHtml()
    {
        return self::agency . '<br/>'
               . '2108 N ST #10819 <br/>'
               . 'Sacramento, CA 95816';
    }
    public $paymentTo = 'California Safe Driver';

    public function accountUrlDirect()
    {
        return A25_Link::to('/account');
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

    public $sendReminders = false;
    
    public static function creditCardRequirementMessage()
    {
        return 'I understand that I have 90 days to complete the course.  I understand that once I begin the course, I cannot be issued a refund.';
    }
    public $contactUsTitle = 'Contact Our Administrative Office';

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::programInfoUrl(), 42, 'Program Information'),
            array(A25_DI::PlatformConfig()->contactUrl(), 44, 'FAQ'));
    }
    public function findACourseUrl()
    {
        return "";
    }
    
    public $onlinePrerequisites = '<p>Please remember that <i><b>it is your responsibility</b></i> to present your
certificate of completion to the court or police department that referred you.
If you are not sure of your due date, refer to your court or police department instructions.</p>';
    public $onlinePrerequisitesSpanish = '<p>Recuerde <i><b>que es su responsabilidad</b></i>
        presentar su certificado de finalizaci&oacute;n al tribunal o al departamento
        de polic&iacute;a que lo refiri&oacute;. Si no est&aacute; seguro de su fecha
        de vencimiento, consulte las instrucciones de su corte o departamento de polic&iacute;a.
</p>';
    public $onlineProviderImagePath = '/images/cobert.png';
    
    const displayBecomeInstructor = false;
    
    public static function loginToEnrollText($course)
    {
        $return = 'In order to enroll for the ';
        if ($course->course_id == 1) {
            $return .= 'DDC-4 Online';
        }
        else {
            $return .= $course->getLocationName();
        }
        $return .= ' course, you must log in. If you do not have an account yet, please register below.';
        return $return;
    }
    public static function loginEnrollMakeDuplicateAccountWarningText()
    {
        return '<p class="required">Note: Each student needs their own individual account.</p>';
    }
    
    public static function loginEnrollDuplicateAccountWarningText()
    {
        return '<p class="required">Note: If you have ever registered with us before,'
        . ' please use the form above to log in to your existing account.</p>';
    }
    
    public $reasonForEnrollmentCourtOrderText = '<i>
  If you are taking this course because of an order or agreement with a court,
  prosecutor, or other criminal justice system organization, please select <b>Court
  Order or Pending Legal Matter</b> and then select the referring court/organization.</i>';
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-11245966378\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11245966378');
</script>";
    
    public $sendFromEmail = 'california@coloradosafedriver.org';

    public static function contactUs()
    {
        $return = '
		<div class="row">
			<div class="col-sm-4">
				<p><strong>Mailing Address</strong></p>
				<p>
				' . PlatformConfig::paymentAddressHtml() . '
				</p>
			</div>
			<div class="col-sm-4">
				<p><img src="https://aliveat25.us/images/phone.png" alt="phone.png" style="margin: 2px;"/><strong>Phone</strong></p>
				<p>
				Ph: ' . PlatformConfig::phoneNumber;

        if (PlatformConfig::faxNumber) {
            $return .= '<br />Fax: ' . PlatformConfig::faxNumber;
        }

        $return .= '
				</p>
                <p><img src="https://aliveat25.us/images/envelope.png" alt="envelope.png" style="margin: 2px;"/><a href="mailto:'
                . A25_DI::PlatformConfig()->contactEmailAddress
                . '">Email Us</a></p>
			</div>
			<div class="col-sm-4">
				<p><strong>Office Hours</strong></p>
				<p>
				' . PlatformConfig::businessHours . '
				</p>
				<p>Closed Major Holidays</p>
			</div>
		</div>';
        return $return;
    }
    
    public $copyStudentEmailToSender = false;
    public function enrollmentReportFields(A25_Record_Enroll $enroll)
    {
        $student = $enroll->Student;
        $course = $enroll->Course;
		$student_id_link = '<a href="' .
			A25_Link::to(
				'/administrator/index2.php?option=com_student&task=viewA&id='
				. $enroll->student_id)
			. '">' . $enroll->student_id . '</a>';
        
        return array(
			'Student ID' => $student_id_link,
			'Status' => $enroll->Status->status_name,
			'Last Name' => $student->last_name,
			'First Name' => $student->first_name,
			'Address' => $student->address_1,
            'Address 2' => $student->address_2,
			'City' => $student->city,
			'State' => $student->state,
			'Zip' => $student->zip,
			'Sex' => $student->gender,
			'Date of Birth' => $student->date_of_birth,
			'Age On Course Date' => $student->age(
					strtotime($course->course_start_date)),
			'Course Date' => $course->date(),
			'Reason for Enrollment' => $enroll->getReasonForEnrollmentName(),
			'Court' => $enroll->courtName(),
            'How they heard' => $enroll->HearAboutType->hear_about_name,
            'Email' => $student->email
		);
    }
    
    public $studentIdToStartNewPassword = 90082;
    public $studentIdToStartNewUserId = 90090;
}
