<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    const STATE_NAME = 'Kentucky';
    const STATE_ABBREV = 'ky';
    const displayPriceOnCertificate = true;
    const certPrinter = 'cert_pdf.php';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;
    public $requireEmail = true;

    const courseDuration = '04:00';

    const minAge = 15;

    public function kickOutInterfaces()
    {
        return array(
            new Acre\A25\SeatExpiration\PostPaymentOptionDeadlineKickOut);
    }

    const defaultCourtFee = 55;
    const defaultCourtSurcharge = 0;
    const instructorClassCreationDeadline = 30;
    const allowInstructorsToEditCourseCapacity = false;
    const discountedDrivingPermitTuition = 0;

    const messageSenderId = 494;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_New();
    }

    const agency = 'Kentucky Safe Driver';
    const phoneNumber = '(502) 699-2295';
    const faxNumber = '';
    public $paymentTo = 'Kentucky Safe Driver';
    
    public static function administrativeAddressHtml()
    {
        return self::agency . '<br/>'
        . '1007 Twilight Trail<br/>'
        . 'Suite 2<br/>'
        . 'Frankfort, KY 40601';
    }

    const businessHours = 'Monday-Friday<br/> 8:00 am-4:00 pm<br/> Eastern Time';

    public static function userCanEditCourseStatus()
    {
        return A25_DI::User()->isAdminOrHigher();
    }
    public function findACourseComments()
    {
        return
        '<p><span style="color: #C00; font-weight: bold;">NOTE:</span>
Successful completion of this course will <b>ONLY</b> give you credit for the
reason you listed at the time of registration. <span style="color: #039; font-style: normal; font-weight: bold;">This
completion is not a two-for-one completion!</span></p>
<p>All students must present either a valid <b>Instruction Permit</b> or
Driver\'s License to the instructor for verification.</p>
<p>To complete the Graduated Licensing Program (GLP) in Kentucky, you must be at
least 15 years of age and hold a valid Kentucky Instruction Permit.</p>
<p>Graduated Licensing Program classes are <span style="color: #C00; font-weight: bold;">FREE</span>
for students who need to meet the mandatory 4-hour GLP requirement.</p>
<p style="font-weight: bold;">Attendance & Cancellation Policy Information:</p>
<p>Please arrive early. Late arrivals will <b>NOT</b> be permitted to attend the
class.</p>
<p>If you are unable to attend your scheduled class, you must cancel or
reschedule as soon as possible, and no later than 24 hours in advance. Students
who fail to cancel and do not attend will be charged a <span style="color: #C00; font-weight: bold;">$'
. A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show Fee</span>. The $'
. A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show Fee will be assessed
each time you fail to cancel and do not attend a scheduled class.</p>
<p>All outstanding fees, including any No-Show Fees, must be paid in full before
any credit will be issued for completing the class.</p>
<p style="font-weight: bold;">Court Diversion Class Information:</p>
<p>Classes taken to satisfy Court Diversion require immediate payment by credit
or debit card at the time of enrollment.</p>
<p><b>Note:</b> This class does not qualify for <b>Kentucky State Traffic School</b>.</p>
<p style="font-weight: bold;">Zoom Class Information:</p>
<p><span style="color: #C00; font-weight: bold;">IMPORTANT:</span>Failure to
comply with any of the Zoom Class Protocols will result in immediate removal
from the course and you will be marked as <b>"FAILED"</b>.</p>
<p><b>Class Registration</b> closes 10 days before the scheduled class date.</p>
<p>All Zoom classes are scheduled in the <b>Eastern Time Zone</b>, not the
<b>Central Time Zone</b>. Please make sure you know your local time zone before
your class.</p>
<p>A Course Workbook is <b>required</b> to attend a Zoom class. It will be
mailed at least 10 days before your scheduled class. <span style="font-style: normal; font-weight: bold;">Please make sure you
enter the correct mailing address at the time of registration.</span></p>
<p>The Zoom registration link will be emailed separately beginning one week
prior to your scheduled class and again on the day of class (or on Friday if
your class is scheduled for Saturday). <b>If you do not see the email in your
Inbox, please check your Junk/Spam folder.</b></p>
<p>Students must have a COMPUTER equipped with a VIDEO CAMERA and Microphone to
take this class. The use of cellphones to take the class will <b>NOT</b> be
permitted.</p>
<p style="font-weight: bold; font-style: normal;">All classes must have a minimum of 12 students
enrolled in order to be held.</p>';
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

    public function courseCommentsDescription()
    {
        return 'Use this field to inform the DDC staff if you experienced a unique'
        . ' situation in your class.  Example: Student(s) asked a controversial'
        . ' question or used a problematic scenario during role play.';
    }
    public $sendClassReminder = true;
    
    const noShowsBeforeNoShowFee = 1;
    public $noShowFeeIsCourseFee = false;
    public $noShowFee = 0;
    
    public $automatedReportRecipients = array('jonathan@appdevl.net',
        'lori@kentuckysafedriver.org', 'tori@kentuckysafedriver.org',
        'debbie@kentuckysafedriver.org');
    public function automatedReportFields($enrollment)
    {
        $student = $enrollment->Student;
        $course = $enrollment->Course;
        $return .= $student->license_no . ',' . $student->license_state . ',' . $student->last_name . ','
                . $student->first_name . ',' . $student->date_of_birth. ','
                . $course->course_start_date . ',' . $student->home_phone . ','
                . $student->email . '
';
        return $return;
    }
    public function automatedReportQuery()
    {
        $yesterday = A25_Functions::stringToDate('Yesterday');
        $query = Doctrine_Query::create()
                ->select('*')
                ->from('A25_Record_Enroll e')
                ->where('e.date_completed = ?', $yesterday)
                ->andWhere('e.reason_id = 2');
        return $query;
    }
    public $automatedReportTitle = 'Kentucky-DDL Alive at 25 report';
    
    public $locationSeatAlertContact = 'Lori';
    
    public static function creditCardRequirementMessage()
    {
        return '<p>I understand that I am paying for services provided by, and '
        . 'that my credit card statement will show a charge from, <i>Colorado '
        . 'State Patrol Family Foundation</i>.</p>'
        . '<p>I understand that all payments are NON-REFUNDABLE and cannot be '
        . 'transferred to any other student account. If I am unable to attend, '
        . 'and cancel 24 hours in advance of the course start time, my payment '
        . 'can be used for another course within 1 year of this payment.<br/>If '
        . 'I do not cancel and do not attend, a no-show fee of $20 for'
        . ' court-ordered students or $10 for other students will be charged.</p>';
    }
    
    public function displayedTuitionOnCourseInfo(A25_Record_Course $course)
    {
        return '0 for Graduated Licensing Program students.</br>$'
                . parent::displayedTuitionOnCourseInfo($course)
                . ' for court-ordered students.';
    }
    public function courseCommentsPrepend()
    {
        return '<p><span style="color: #C00; font-weight: bold;">NOTE:</span> Successful completion of this course will <b>ONLY</b> give
you credit for the reason you listed at the time of registration.
<span style="color: #039; font-weight: bold; font-style: italic;">This
completion is not a two-for-one completion!</span>
<p>All students must present either a valid <b>Instruction Permit</b> or
Driver\'s license to the instructor for verification.</p>
<p style="font-weight: bold;">Attendance & Cancellation Policy Information:</p>
<p>Please arrive early. Late arrivals will <b>NOT</b> be permitted to attend the class.</p>
<p>If you are unable to attend your scheduled class, you must cancel or
reschedule as soon as possible, and no later than 24 hours in advance. Students
who fail to cancel and do not attend will be charged a
<span style="color: #C00; font-weight: bold;">$' .
A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show Fee</span>. The $' .
A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show Fee will be assessed
each time you fail to cancel and do not attend a scheduled class.</p>
<p>All outstanding fees, including any No-Show Fees, must be paid in full before
any credit will be issued for completing the class.';
    }
    const instructorTimesheetForInstructors = false;
    public $showCanceledCoursesToPublic = true;
    
    public static function loginEnrollDuplicateAccountWarningText()
    {
        return '<p class="required">Note: If you have previously registered for, have already taken, or have missed attendance to ' . PlatformConfig::courseTitleHtml() . ' in the past, <u>please do not create a new account</u>.  Please login to your current or past account. '
                . 'Duplicate student accounts could be subject to a $25.00 fee.</p>';
    }
    public $courseInfoCertificateMessage = '';
    
    public $courseInfoCertificateMessageVirtual = '';

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::findACourseUrl(), 19, 'Find A Course'));
    }
    
    public $courseRosterFields = array('Student ID', 'Name', 'Reason For Attending', 'Status', 'Phone', 'Age', 'Special Needs', 'Driver License #', 'Date of Birth');
    public $showSMSCourseRosterField = true;
    
    public $contactEmailAddress = 'information@kentuckysafedriver.org';
    public $sendFromEmail = 'kentucky@coloradosafedriver.org';
    
    public function licenseStatuses()
    {
        $license_status = array();
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_drivingPermit, 'I have an Instruction Permit<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_valid, 'I have a valid Driver\'s License<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_suspended, 'I have a suspended Driver\'s License<br />');
        return $license_status;
    }
    
    public $confirmLicenseNo = true;
    
    // Only used in CourtDocketNumber plugin
    public $courtDocketNumberName = 'Case Number';
    
    const firstInstructorReminderHoursBefore = 9999;    // Set to a high number so first reminder comes when they are assigned the course
    const secondInstructorReminderHoursBefore = 168;    // 7 days
    
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
            'Primary Phone' => $student->home_phone,
            'Email' => $student->email
		);
    }
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-LJC24N7F1W\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LJC24N7F1W');
</script>";
    
    public function displayTuitionOnCourseInfo()
    {
        return false;
    }
    
    public $allowCourtSurchargeWaive = false;
    
    public $twilioPhoneNumber = '7204662898';
    
    public $adCode = '<!--- UNDERDOGMEDIA EDGE_aliveat25.us JavaScript ADCODE START--->
<script data-cfasync="false" language="javascript" async referrerpolicy="no-referrer-when-downgrade" src="https://udmserve.net/udm/img.fetch?sid=19464;tid=1;dt=6;"></script>
<!--- UNDERDOGMEDIA EDGE_aliveat25.us JavaScript ADCODE END--->
        <script src="https://js.adsrvr.org/up_loader.1.1.0.js" type="text/javascript"></script>
        <script type="text/javascript">
            ttd_dom_ready( function() {
                if (typeof TTDUniversalPixelApi === \'function\') {
                    var universalPixelApi = new TTDUniversalPixelApi();
                    universalPixelApi.init("cvz2ocq", ["m8abi4g"], "https://insight.adsrvr.org/track/up");
                }
            });
        </script>';
    
    const displayBecomeInstructor = false;

    public static function contactUs()
    {
        $return = '
		<div class="row">
			<div class="col-sm-4">
				<p><strong>Address</strong></p>
				<p>
				' . PlatformConfig::administrativeAddressHtml() . '
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
    
    public $acceptedCards = 'Visa/Mastercard/American Express';
    
    public $courtOrderedReasonTypeList = array(
    A25_Record_ReasonType::reasonTypeId_CourtOrdered,
    A25_Record_ReasonType::reasonTypeId_PendingLegalMatter
    );
    public $pendingLegalMatterReasonTypeList = array(); //Blank because Pending Legal Matter is included in court ordered list
    
    public function cancellationTextOnAccountPage($course)
    {
        return 'If you do not cancel and do not
attend, you will be charged a <span style="color: #C00; font-weight: bold;">$' .
A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show fee</span> that must be paid before you
will be given credit for completing a class.';
    }
    
    public $hasCertificates = false;
    
    public $timezone = 'Eastern Time';
    
    public $showWhenRegistrationCloses = true;
    
    public $createAccountComments = '<span style="color: #C00; font-weight: bold;">IMPORTANT:</span>
Providing incorrect information may result in
<span style="text-decoration: underline; font-weight: bold;">NOT receiving credit</span>
for completing the course. <span style="text-decoration: underline; font-weight: bold;">
All student information must match exactly as it appears on your Instruction Permit or Driver\'s License.</span><br/>';
}
