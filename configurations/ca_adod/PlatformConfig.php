<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '54Y3Bswj7Cc';
    const AUTHORIZE_NET_TRAN_KEY = '6u23Pqp7NgQ476e9';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;
    public $requireEmail = true;

    const STATE_NAME = 'California';
    const STATE_ABBREV = 'ca';
    const courseTitle = 'Attitudinal Dynamics of Driving';
    const siteTitle = 'Attitudinal Dynamics of Driving - California';
    const mailingAddressName = 'Attitudinal Dynamics of Driving';

    // This setting is necessary for ADOD so that the yellow 'selected state'
    // box is hidden
    const isAState = false;

    const displayPriceOnCertificate = false;

    const defaultCourtFee = 219;
    const courseDuration = '08:00';

    const agency = 'California Safe Driver';
    const phoneNumber = '1-877-525-DRIVE (3748)';
    const faxNumber = null;
    const businessHours = 'Monday-Friday 9am-5pm Pacific Time';

    const minAge = 0;
    const maxAge = 100;

    const displayBecomeInstructor = false;
    const allowInstructorsToSeeCourseRevenue = false;

    const turnOnRequestSupplies = false;
    const turnOnInstructorTimesheet = false;

    const rowForBigTextMiddleOnCertificate = 97;

    const messageSenderId = 2;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_Adod();
    }

    public function colorScheme()
    {
        return new A25_ColorScheme_Orange();
    }

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::findACourseUrl(), 19, 'Find A Course'),
            array(PlatformConfig::programInfoUrl(), 42, 'Program Information'),
            array(A25_DI::PlatformConfig()->contactUrl(), 44, 'FAQ'));
    }

    const courseTitleFull = PlatformConfig::courseTitle;
    const prettyLandingPageUrl = 'adod.californiasafedriver.com';
    const emailLogoPath = 'images/adod_full_logo.jpg';

    public function accountUrlDirect()
    {
        return PlatformConfigAbstract::accountUrl();
    }

    public static function creditCardRequirementMessage()
    {
        return '<p>I understand that I am paying for services provided by, and
that my credit card statement will show a charge from, <i>'
. PlatformConfig::agency . ' ' . PlatformConfig::courseTitle . '</i>.</p>
<p>I acknowledge that I have read and accept the registration guidelines,
including but not limited to:
<ul><li>No late students will be allowed to attend class</li>
<li>Students who are late or miss a class sacrifice their tuition</li>
<li>Students must complete the entire course, actively participate, and follow
rules set by the instructor</li>
<li>We do not offer refunds, but you may change your class reservation if you
cancel at least 30 days in advance</li></ul></p>
<p>I understand that this course is not considered by the California Department
of Motor Vehicles to be a point reduction course, nor does it guarantee that my
insurance company will provide me a reduction in my insurance premiums.';
    }

    /**
     * California counts no-shows in course revenue, since they don't ever give
     * credits to no-shows.  The next 2 declarations allow for that in course
     * revenue calculations.
     */
    public static function enrollmentStatusesNotElligibleForCourseRevenue()
    {
        // Get all parent statuses
        $statuses = parent::enrollmentStatusesNotElligibleForCourseRevenue();

        // Remove no show status if in statuses
        $noShowIndex = array_search(A25_Record_Enroll::statusId_noShow, $statuses);
        if ($noShowIndex !== false) {
            // Remove noShow value
            unset($statuses[$noShowIndex]);
            // Reorder array
            $statuses = array_values($statuses);
        }

        // Add Registered, since they don't want unpaid enrollments included
        array_push($statuses, A25_Record_Enroll::statusId_registered);

        return $statuses;
    }

    public static function orderItemTypesElligibleForCourseRevenue()
    {
        $types = parent::orderItemTypesElligibleForCourseRevenue();
        $types[] = A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows;
        return $types;
    }

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

    public function findACourseComments()
    {
        return '<p><u>Students enrolling in a virtual ' . PlatformConfig::courseTitle . ' course are agreeing to the following:</u>
<ol>
<li>Students MUST provide a reliable <b>email</b> address as this is how the Zoom meeting and Kahoot links will be sent to you.</li>
<li>Students MUST provide a valid <b>mailing address</b> to receive both their workbook and certificate of completion (mailed separately).</li>
<li>Students MUST have a reliable <b>internet connection</b>. If students lose their internet connection for more than 5 minutes, they will be <b>REQUIRED</b> to take the class over and pay for it again.</li>
<li>Students MUST use a <b>computer or tablet</b>, not a phone, to attend the class.</li>
<li>Course completion certificates will be mailed to students within 5 business days following the completion of their course.  <u>Do NOT enroll into a class that is within 2 weeks of your court date, as the certificate of completion will not be delivered in time.</u></li>
</ol>
<b>BY REGISTERING, YOU ARE CERTIFYING YOU UNDERSTAND AND WILL FOLLOW ALL GUIDELINES DURING THE CLASS.</b></p>
<p style="font-size: 16px; font-weight: bold;">These courses teach <u>California Laws</u> as required by California courts, probation and police departments.</p>';
    }

//    public function locationComments()
//    {
//        return 'If you are already enrolled for a course and are looking for the room number where the course is being held, please check the course notes at <a href=' . A25_Link::to('account') . '>Your Account</a>.';
//    }

    const noShowsBeforeNoShowFee = 1;
    public $paymentTo = 'California Safe Driver';
    public function kickOutInterfaces()
    {
        return array(
            new Acre\A25\SeatExpiration\PostPaymentOptionDeadlineKickOut,
            new Acre\A25\SeatExpiration\DaysBeforeCourseKickOut);
    }
    const timeEnrolledBeforeReminderSent = 0;
    const secondInstructorReminderHoursBefore = 24; // 1 day
    const classReminderMinHoursBefore = 1;
    const classReminderMaxHoursBefore = 168; // 7 days
    const secondClassReminderMinHoursBefore = 0;
    const secondClassReminderMaxHoursBefore = 1;
    public $sendClassReminder = true;
    public $firstClassReminderBody = 'CAFirstReminderBody.phtml'; // File in A25/Remind/Students
    public $secondClassReminderBody = 'CASecondReminderBody.phtml'; // File in A25/Remind/Students

    public function cancellationPolicyForEmail()
    {
        return 'If you are unable to attend this class, please <a href="'
        . PlatformConfig::accountUrlDirect() . '">cancel or
reschedule</a> your class as soon as possible or at least 30 days in
advance.
';
    }
    
    public function displayTuitionOnCourseInfo()
    {
        return false;
    }
    
    public function courtTextForCertsTop(A25_Record_Enroll $enroll)
    {
        return strtoupper('**Court certified**');
    }
    public function courtTextForCertsMiddle(A25_Record_Enroll $enroll)
    {
        return strtoupper('**Court certified**');
    }
    
    public function marijuanaPolicyLink()
    {
        return A25_Link::to('content/view/2');
    }

    public function courseCommentsPrepend()
    {
        return '<p>Please <b>have your photo ID</b> (REQUIRED). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.</p>
';
    }
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-11280801881\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11280801881');
</script>";
    
    public $sendFromEmail = 'ca-adod@coloradosafedriver.org';

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
    
    public $allClassesVirtual = true;
    
    public static function aboutUs()
    {
        return '';
    }
    public function programInfoUrl()
    {
        return A25_Link::to('component/option,com_location/task,whythisworks/Itemid,42/');
    }
    
    public $courseInfoCertificateMessageVirtual = '<p><b>Proof of completion:</b><br/>
      Students who successfully complete the entire class will be mailed a
      certificate of completion. Please allow up to 14 days for your certificate
      to arrive via US Mail.</p>';
    
    public $courseInfoCertificateMessageVirtualSpanish = '<p><b>Comprobante de finalizaci&oacute;n:</b><br/>
      Los estudiantes que completen con &eacute;xito toda la clase
      recibir&aacute;n por correo un certificado de finalizaci&oacute;n. Por
      favor, permita hasta 14 d&iacute;as para que su certificado llegue por
      correo de EE. UU.</p>';
    
    public $kahootLink = 'https://create.kahoot.it/share/adod-waiting-room-kahoot/3f43a46c-bc4e-4558-a651-60bb5b87ae90';
    
    public $allowResendReminders = true;
    
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
    
    public $contactUsTitle = 'Answers to Common Questions / Contact Us';
}
