<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    // Kentucky DDC 4
    const STATE_NAME = 'Kentucky';
    const STATE_ABBREV = 'ky';
    const displayPriceOnCertificate = true;
    const certPrinter = 'cert_pdf.php';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;
    public $requireEmail = true;

    const courseDuration = '04:00';

    const minAge = 1;
    const maxAge = 199;

    public $kickOutBeforeDeadline = '7 days';

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
    const mailingAddressName = 'Kentucky Safe Driver';
    
    public static function administrativeAddressHtml()
    {
        return self::mailingAddressName . '<br/>'
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
        '<p style="color: #dd0000; text-decoration: underline;">Payment is required at the time of ENROLLMENT.
</p>
<p>All Zoom classes are scheduled on <b>EASTERN</b> time zone. The Zoom
registration link will be emailed separately to you starting a week before your class date.</p>
<p>If you do not receive the emails in your Inbox, check your Junk/Spam email,
or contact our office at ' . self::phoneNumber . ' during normal business hours:</p>
<p>' . self::businessHours . '</p>
<p>Closed Holidays</p>';
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
        'lori@kentuckysafedriver.org');
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
    public $automatedReportTitle = 'Kentucky-DDL DDC4 report';
    
    public $locationSeatAlertContact = 'Lori';
    
    public static function creditCardRequirementMessage()
    {
        return '<p>I understand that I am paying for services provided by, and '
        . 'that my credit card statement will show a charge from, <i>Colorado '
        . 'State Patrol Family Foundation</i>.</p>'
        . '<p>I understand that all payments are NON-REFUNDABLE and cannot be '
        . 'transferred to any other student account. If I am unable to attend, '
        . 'and cancel 24 hours in advance of the course start time, my payment '
        . 'can be used for another course within 1 year of this payment.</p>';
    }
    
    public function courseCommentsPrepend()
    {
        return "<p>All students must present their driver permit/license to the instructor.</p>
            <p>Be sure to <b>arrive early</b>, as <em>late arrivals are not allowed to attend</em>.</p>";
    }
    const instructorTimesheetForInstructors = false;
    
    const courseTitle = 'Kentucky MDDC';
    const courseTitleFull = 'Kentucky MDDC';
    const siteTitle = 'Kentucky MDDC';
    
    const isAState = false;

    public function colorScheme()
    {
        return new A25_ColorScheme_Orange();
    }

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::findACourseUrl(), 19, 'Find A Course'));
    }
    public $contactUsTitle = 'Contact Us';
    public $contactEmailAddress = 'sts@kentuckysafedriver.org';
    public $sendFromEmail = 'kentucky@coloradosafedriver.org';
    
    public static function loginEnrollDuplicateAccountWarningText()
    {
        return '<p class="required">Note: If you have previously registered for, have already taken, or have missed attendance to ' . PlatformConfig::courseTitleHtml() . ' in the past, <u>please do not create a new account</u>.  Please login to your current or past account. '
                . 'Duplicate student accounts could be subject to a $25.00 fee.</p>';
    }
    public $courseInfoCertificateMessage = '';
    
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
    
    public function displayTuitionOnCourseInfo()
    {
        return false;
    }
    
    public function privateCourseDescription($name)
    {
        return 'This course is reserved for ' . $name . '.
</p><p>
To ensure that only valid students enroll, this course has been assigned a
registration code. If you do not have the code, please contact ' . $name .
' at ' . self::phoneNumber . ', and they should be able to provide it to you. Calls
received after 4:00 PM Eastern Time / 3:00 PM Central Time will be returned the 
next business day. CLOSED HOLIDAYS';
    }
    
    // Only used in CourtDocketNumber plugin
    public $courtDocketNumberName = 'Case Number';
    
    public $allowCourtSurchargeWaive = false;
    
    public $twilioPhoneNumber = '7204662898';
    
    public function cancellationPolicyForEmail()
    {
        return 'For all classes other than State Traffic School, if you will be'
. ' unable to attend please <a href="' . PlatformConfig::accountUrlDirect()
. '">cancel or reschedule</a> your class as soon as possible or at least 24
hours in advance.
';
    }
    
    public function accountUrlDirect()
    {
        return PlatformConfig::accountUrl();
    }
    
    public function reasonForEnrollmentCourtOrderText()
    {
        return '<i>
For court order enrollments, you must contact Kentucky Safe Driver at '
. PlatformConfig::phoneNumber . ' between the hours of 8:00am and 4:00pm eastern
time on Monday through Friday, excluding holidays.
<u>Criminal justice system certificates of completion appear differently than
non-justice system related certificates</u>.</i>';
    }
    
    public $forbidFrontEndCourtEnrollments = true;
    
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
    
    const reasonTypeId_Diversion = 13;
    public $courtOrderedReasonTypeList = array(
    A25_Record_ReasonType::reasonTypeId_CourtOrdered,
    A25_Record_ReasonType::reasonTypeId_PendingLegalMatter,
    self::reasonTypeId_Diversion
    );
    public $pendingLegalMatterReasonTypeList = array(); //Blank because Pending Legal Matter is included in court ordered list
    
    public $timezone = 'Eastern Time';
    
    public $courseRosterFields = array('Student ID', 'Name', 'Reason For Attending', 'Status', 'Phone', 'Age', 'Special Needs');
    public $showSMSCourseRosterField = true;
    
    public $showWhenRegistrationCloses = true;
}
