<?php

use Acre\A25\Printing\NscCert;
use Acre\A25\Printing\PdfGenerator;

abstract class PlatformConfigAbstract
{
    const webmasterEmail = 'jonathan@appdevl.net';
    const isAState = false;

    public $courseIsOnline = false;

    const STATE_NAME = "Colorado";

    /**
     * This should only be true if the deployment is simply a link to state sites.
     */
    const isNationalPortal = false;

    const displayPriceOnCertificate = true;

    const linkToTuitionRates = false;
    public $acceptChecks = true;
    public $acceptOnlyCreditCards = false;
    public $acceptCreditCards = true;
    public $requireEmail = false;
    public $requireSecondaryPhone = false;

    const courseDuration = '04:00';

    /**
     * These ages include the age listed as valid.  So a min age of '13' means
     * that a student can be 13 or older.  A max age of 24 means a student can
     * be 24, but not 25.
     */
    const minAge = 13;
    const maxAge = 24;

    const allowCourtReferrals = true;
    const allowCourtReferralsOnly = false;
    const defaultCourtFee = 89;
    public $nsfFee = 35;

    public function chargesForCourse()
    {
        return (PlatformConfig::defaultCourtFee > 0);
    }

    const defaultCourtSurcharge = 0;
    const instructorClassCreationDeadline = 0;
    const allowInstructorsToSeeCourseRevenue = true;
    const allowInstructorsToEditCourseCapacity = true;

  /**
   * Rather than controlling this feature here, "Instructor Supplies Request"
   * should be a plugin.  Issue #144 will convert this to a plugin.
   */
    const turnOnRequestSupplies = true;

  /**
   * Rather than controlling this feature here, "Instructor Supplies Request"
   * should be a plugin.  Issue #145 will convert this to a plugin.
   */
    const turnOnInstructorTimesheet = true;
    // If true, instructors can fill out timesheets, otherwise only admins can
    const instructorTimesheetForInstructors = true;

    const messageSenderId = 62;

  /**
   * @todo-jon - verify that all states use this certificate printing file, and
   * if so, remove any others, and remove this variable from all PlatformConfig
   * files, hard-coding the value instead where it is used.
   */
    // This should be a file name in /administrator/components/com_course
    const certPrinter = 'cert_pdf.php';

    /**
     * This section only applies to the CourtsOnCerts plugin.  If I every get
     * more than one setting here, consider moving this to its own Config class.
     */
    const hideCourtInfoOnNonPublicCerts = false;
    public function courtTextForCertsTop(A25_Record_Enroll $enroll)
    {
        return strtoupper('**Valid only for current legal proceedings in '
            . $enroll->courtName() . '**');
    }
    public function courtTextForCertsMiddle(A25_Record_Enroll $enroll)
    {
        return strtoupper('**Valid only for current legal proceedings in '
            . $enroll->courtName() . '**');
    }

    /**
    * When searching for nearby courses by zip code, what is the initial
    * limit for search radius, in miles?
    */
    const defaultSearchRadius = 25;

    /**
     * When an exact zip code match cannot be found, what is the search
     * radius within the database to find the nearest zip?
     */
    const zipSearchLimit = 50;

    /**
     * When searching for courses near a zip, how many course results are too
     * much? Suggest to the user to reduce their search radius.
     */
    const zipResultUpperLimit = 100;

    /**
    * Message to students who attempt to make payment on a course that is already full.
    */
    const studentCourseIsFullMsg = "Unfortunately you may not make payment because your course has filled to capacity and all seats are taken. Please cancel your enrollment in your profile area and enroll in a different course. Please do not attempt to attend this class as it is full and we do not accept payments at the door.";

    const defaultPublished = 1;
    const defaultParent = 0;

  /**
   * How long a student has to pay after registering for a course
   */
    public $kickOutBeforeDeadline = 'never';
    public $kickOutAfterDeadline = '30 minutes';
    public $kickOutBeforeCourseDeadline = '15 days';

    public static function creditCardRequirementMessage()
    {
        return 'I understand that all payments are NON-REFUNDABLE and cannot be '
        . 'transferred to any other student account. If I am unable to attend, '
        . 'and cancel 24 hours in advance of the course start time, my payment '
        . 'can be used for another course within 1 year of this payment.<br/>If '
        . 'I do not cancel and do not attend, a no-show fee of $20 for standard '
        . 'tuition or $10 for discounted tuition will be charged.';
    }

    const courseTitle = 'Alive at 25';
    const courseTitleFull = 'Alive at 25 Driver\'s Awareness';
    const siteTitle = 'Alive at 25 Driving Education';
  // These functions should be used in HTML code
    public static function courseTitleHtml()
    {
        return htmlentities(PlatformConfig::courseTitle, ENT_COMPAT | ENT_HTML401, 'UTF-8');
    }
    public static function courseTitleFullHtml()
    {
        return htmlentities(PlatformConfig::courseTitleFull, ENT_COMPAT | ENT_HTML401, 'UTF-8');
    }
    public static function siteTitleHtml()
    {
        return htmlentities(PlatformConfig::siteTitle, ENT_COMPAT | ENT_HTML401, 'UTF-8');
    }

    public $paymentTo = self::courseTitle;

    const displayBecomeInstructor = true;

    const schoolType = 'High School';

    const rowForBigTextMiddleOnCertificate = 100;
    const rowForBottomCertPrintingLine = -23.5;
    const rowForBottomStudentName = -78.5;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_Old();
    }

    public function colorScheme()
    {
        return new A25_ColorScheme_Green();
    }

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::findACourseUrl(), 19, 'Find A Course'),
            array(PlatformConfig::programInfoUrl(), 42, 'Program Information'),
            array(A25_Link::toNational('content/view/17/21/'), 21,
                'Learn More'),
            array(A25_Link::toNational('faq/'), 44, 'FAQ'),
            array(A25_Link::toNational('content/blogsection/2/40/'), 40,
                'In The News'),
            array(A25_Link::toNational('content/view/32/50/'), 50, 'Articles'),
            array(A25_Link::toNational('content/view/9/9/'), 9,
                'Shop Our Store'));
    }

  /**
   * @todo-jon-medium-medium - Refactor this to use the new EnrollmentStatus
   * "declarative" classes.  This should include:
   * 1. Rewrite this function to be similar to Enroll::statusList() functions.
   *    The new function in the EnrollmentStatus classes should be named something
   *    like eligibleForCourseRevenue()
   *    (Duplication is okay until a later step)
   * 2. Create a new PlatformConfig variable called PlatformConfig::countNoShowAsCourseRevenue.
   *    Default in PlatformConfigAbstract should be false.  But California, as
   *    well as any other states which override this below function, should get
   *    that set to true.  Then Update A25_EnrollmentStatus_NoShow->eligibleForCourseRevenue()
   *    to return its value based on the PlatformConfig setting.
   * 3. Move this function from PlatformConfig to Enroll, since the overriden
   *    versions will no longer be necessary.
   */
    public static function enrollmentStatusesNotElligibleForCourseRevenue()
    {
        return array(
            A25_Record_Enroll::statusId_canceled,
        A25_Record_Enroll::statusId_kickedOut,
            A25_Record_Enroll::statusId_noShow
        );
    }

    public static function orderItemTypesElligibleForCourseRevenue()
    {
        return array(
            A25_Record_OrderItemType::typeId_CourseFee
        );
    }

    public $contactUsTitle =
        'Contact Your Local Alive at 25 Administrative Office';

    public static function contactUs()
    {
        $return = '
		<div class="row">
			<div class="col-sm-3">
				<p><strong>Payment Address</strong></p>
				<p>
				' . PlatformConfig::paymentAddressHtml() . '
				</p>
			</div>
			<div class="col-sm-3">
				<p><strong>Administrative Office Address</strong></p>
				<p>
				' . PlatformConfig::administrativeAddressHtml() . '
				</p>
			</div>
			<div class="col-sm-3">
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
			<div class="col-sm-3">
				<p><strong>Office Hours</strong></p>
				<p>
				' . PlatformConfig::businessHours . '
				</p>
				<p>Closed Major Holidays</p>
			</div>
		</div>';
        return $return;
    }

    public $contactEmailAddress = ServerConfig::adminEmailAddress;

    /**
     * If you do not want to have an 'About Us' page, simply override the
     * function to return an empty string, or create an empty file in
     * PlatformTemplates/aboutUs.phtml.
     *
     * @return string
     */
    public static function aboutUs()
    {
        return self::openPlatformTemplate('aboutUs.phtml');
    }
    public static function whyThisWorks()
    {
        return self::openPlatformTemplate('whyThisWorks.phtml');
    }
    public static function courses()
    {
        return self::openPlatformTemplate('courses.phtml');
    }

    public static function paymentInstructions()
    {
        return self::openPlatformTemplate('paymentInstructions.phtml');
    }

    public static function registrationGuidelines()
    {
        return self::openPlatformTemplate('registrationGuidelines.phtml');
    }

    public static function supportUs()
    {
        return self::openPlatformTemplate('supportUs.phtml');
    }

    public static function curriculum()
    {
        return self::openPlatformTemplate('curriculum.phtml');
    }

    public static function evidence()
    {
        return self::openPlatformTemplate('evidence.phtml');
    }

    public static function resources()
    {
        return self::openPlatformTemplate('resources.phtml');
    }

    public static function certificate()
    {
        return self::openPlatformTemplate('certificate.phtml');
    }

    public static function whyNSC()
    {
        return self::openPlatformTemplate('whyNSC.phtml');
    }

    public static function diversity()
    {
        return self::openPlatformTemplate('diversity.phtml');
    }

    public static function openPlatformTemplate($filename)
    {
        ob_start();
        include self::platformTemplateFilePath($filename);
        return ob_get_clean();
    }

    public static function platformTemplateFilePath($filename)
    {
        $override = dirname(__FILE__) . '/PlatformTemplates/' . $filename;
        if (file_exists($override)) {
            return $override;
        } else {
            return dirname(__FILE__) . '/PlatformTemplateDefaults/' . $filename;
        }
    }

    public static function loginToEnrollText($course)
    {
        return 'In order to enroll for the ' . PlatformConfig::courseTitleHtml() . ' course to be held at ' . $course->getLocationName() . ' on ' . $course->formattedDate('course_start_date',_DATE_FORMAT) . ', you must log in.  If you do not have an account yet, please register below.';
    }
    public static function loginEnrollDuplicateAccountWarningText()
    {
        return '<p class="required">Note: If you have previously registered for, have already taken, or have missed attendance to ' . PlatformConfig::courseTitleHtml() . ' in the past, <u>please do not create a new account</u>.  Please login to your current or past account.  If you create a duplicate account, this may increase the time it takes for us to issue a certificate upon completion.</p>';
    }
    public static function loginEnrollMakeDuplicateAccountWarningText()
    {
        return '<p class="required">Note: Each student needs their own individual account. (i.e. siblings need separate accounts)</p>';
    }
    public static function registerEnrollText()
    {
        return 'To complete registration for the ' . PlatformConfig::courseTitleHtml() . ' course to be held at <strong>%s</strong> on <strong>%s</strong>, you must first register as a student.';
    }
    public static function registerNoEmailText()
    {
        return 'To register for the ' . PlatformConfig::courseTitleHtml() . ' course, you must have a valid e-mail address.';
    }
    public static function registerNoDOBText()
    {
        return 'To register for the ' . PlatformConfig::courseTitleHtml() . ' course, you must enter a valid date of birth.';
    }
    public static function registerBadDOBText()
    {
        return 'To register for the ' . PlatformConfig::courseTitleHtml() . ' course, you must currently be between the ages of 13 and 24.';
    }
    public static function emailTemplateHtml()
    {
        return ServerConfig::webRoot . '/templates/aliveat25/emailTemplate.php';
    }
    const registerExistingAccountText = 'Although you indicated that you had not already registered, an account already exists with this e-mail address. If you remember your password, please log on now.';
    const registerNewAccountText = 'Registration will be complete when payment is made for the class. Payment instructions will be included with your registration confirmation.\nThe Department of Revenue requires that you provide your full legal name.\nIf you are taking this class prior to a court appearance you will be required to pay $75.00.';

    const phoneNumber = '(720) 269-4046';
    const faxNumber = '(303) 237-2067';
    const businessHours = 'Monday-Friday, 8:30am-4:30pm, Mountain Time';
    const mailingAddressName = 'Alive At 25';
    public static function paymentAddressHtml()
    {
        return PlatformConfig::administrativeAddressHtml();
    }
    public static function paymentAddressText()
    {
        return preg_replace('#<br\s*/?>#', "\n", PlatformConfig::administrativeAddressHtml());
    }
    public static function administrativeAddressHtml()
    {
        return PlatformConfig::mailingAddressName . '<br/>'
             . '55 Wadsworth Blvd<br />'
             . 'Lakewood, CO 80226<br />';
    }
    public static function forgotLoginContactInfo()
    {
        return 'If you have forgotten your login information, '
              . 'please <a href="' . PlatformConfig::contactUrl() . '">contact us</a>.';
    }
    const agency = 'Colorado State Patrol Family Foundation';
    const shortAgency = self::agency;

    /**
     * in hours
     */
    const timeEnrolledBeforeReminderSent = 24;
    const firstInstructorReminderHoursBefore = 168;    // 7 days
    const secondInstructorReminderHoursBefore = 0;    // Off by default
    const classReminderMinHoursBefore = 72;  // 3 days
    const classReminderMaxHoursBefore = 120;  // 5 days
    const secondClassReminderMinHoursBefore = 24;
    const secondClassReminderMaxHoursBefore = 36;
    // Send reminders in remind.php. If false, no reminders will be sent.
    public $sendReminders = true;
    public $sendClassReminder = false;
    public $firstClassReminderBody = 'UpcomingClassBody.phtml'; // File in A25/Remind/Students
    public $secondClassReminderBody = 'UpcomingClassBody.phtml'; // File in A25/Remind/Students
    const paymentReminderHoursBeforeKickOut = 96;  // 4 days
    public $firstPaymentReminderMaxHoursAfterEnrollment = 24;
    public function firstPaymentReminderMinTimeAfterEnrollment()
    {
        return A25_DI::PlatformConfig()->kickOutAfterDeadline;
    }

    const prettyLandingPageUrl = 'www.aliveat25.us';
    const emailLogoPath = 'images/logo.gif';

    const contactPath = '/component/option,com_location/task,contactus/';
    const findACoursePath = 'find-a-course';
    const accountPath = '/account';
    const programInfoPath = 'component/option,com_location/task,aboutus/Itemid,42/';
    const faqPath = 'faq';

    public function contactUrl()
    {
        return '/component/option,com_location/task,contactstate/';
    }
    public function accountUrl()
    {
        return A25_Link::https(PlatformConfig::accountPath);
    }
    public function nationalAccountUrl()
    {
        return A25_Link::https(PlatformConfigAbstract::accountPath);
    }
    public function accountUrlDirect()
    {
        return PlatformConfig::accountUrl();
    }
    public function faqUrl()
    {
        return '/component/option,com_location/task,faq/Itemid,44/';
    }
    public function findACourseUrl()
    {
        return '/component/option,com_location/task,findcourse/Itemid,19/';
    }
    public function programInfoUrl()
    {
        return '/component/option,com_location/task,programinfo/Itemid,42/';
    }
    public static function surchargeFootnote($amount)
    {
        return 'Colorado Revised Statute 42-4-1717, requires defendants who have
violated traffic laws and who agree to or are ordered by a court to attend a
driver improvement school/course, to pay a $'
        . preg_replace('/\.00/', '', $amount) . ' penalty surcharge.  This
surcharge is collected by the driver improvement school and is remitted in full
to the Colorado Department of Revenue.  The funds generated through the
collection of the penalty surcharge are used by the Colorado Department of
Revenue to underwrite the administrative costs associated with a driver
improvement school quality control program established by this statute.  Driver
improvement schools do not retain any part of the surcharge.';
    }
    public static function userCanEditCourseStatus()
    {
        return true;
    }
    public function findACourseComments()
    {
        return '';
    }
    public function findACourseEspanolComments()
    {
        return $this->findACourseComments();
    }
    public function locationComments()
    {
        return '';
    }

    const reasonTypeId_PendingLegalMatter_number = 999;

    public function courseCommentsPrepend()
    {
        return '<p>Please <b>bring a photo ID</b> (if available). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.</p>
';
    }

    public function tuitionDetails($tuition)
    {
        return '';
    }

    public $courtOrderedReasonTypeList = array(
    A25_Record_ReasonType::reasonTypeId_CourtOrdered
    );
    public $pendingLegalMatterReasonTypeList = array(
    A25_Record_ReasonType::reasonTypeId_PendingLegalMatter
    );

    const noShowsBeforeNoShowFee = 2;

    public function courseCommentsDescription()
    {
        return '';
    }

    public function slowPaymentCertificateText(A25_Record_Course $course)
    {
        $lateDeadline = strtotime($course->course_start_date . ' -' .
                $course->getSetting('late_fee_deadline') . ' hours');
        if ($lateDeadline > strtotime('now')) {
                return 'Please make sure payment arrives before '
                . date('g:i a \o\n l, F j', $lateDeadline) . ' to avoid a late
                payment fee of $' . $course->getLateFee() . ' and to ensure your
                certificate of completion will be pre-printed and available at the class. ';
        }
    }
    public function includeOnCheckText(A25_Record_Student $student)
    {
        return 'Please include the
student\'s name and ' . PlatformConfig::courseTitleHtml()
        . ' student ID number (#' .  $student->student_id . ') with your payment.
';
    }
    public function cancellationPolicyForEmail()
    {
        return 'If you are unable to attend this class, please <a href="'
        . PlatformConfig::accountUrlDirect() . '">cancel or
reschedule</a> your class as soon as possible or at least 24 hours in
advance.
';
    }
    public function cancellationPolicyForEmailSpanish()
    {
        return $this->cancellationPolicyForEmail();
    }
    public function displayTuitionOnCourseInfo() 
    {
        return true;
    }
    public function displayedTuitionOnCourseInfo(A25_Record_Course $course)
    {
        return intval($course->getSetting('fee'), 0);
    }

    public $sendFromEmail = 'info@coloradosafedriver.org';
    public $evaluationTemplate = 'CaliforniaCourseEvaluationTemplate.pdf';
    public function kickOutInterfaces()
    {
        return array(
            new Acre\A25\SeatExpiration\PostPaymentOptionDeadlineKickOut,
            new Acre\A25\SeatExpiration\DaysAfterEnrollingKickOut);
    }
    public function certificatePrinter()
    {
        return new NscCert(new PdfGenerator(), A25_ListenerManager::all(), A25_DI::PlatformConfig()->certPdfSettings());
    }
    public function siteTemplateHeader()
    {
        return new Acre\A25\Template\AliveAt25UsHeader(
            new Acre\A25\Template\StandardTopMenuContainer(
                new Acre\A25\Template\StandardTopMenu()
            )
        );
    }
    // Options:
    // Authorize.net AIM Integration: 'credit-card-payment'
    // Authorize.net SIM Integration: 'sim-form'
    // Lexis Nexis Payment Form: 'lnps-form'
    public $paymentForm = 'sim-form';
    
    public function marijuanaPolicyLink()
    {
        return A25_Link::to('content/view/42');
    }
    
    // Most states charge the student the full price of the course for no-shows
    public $noShowFeeIsCourseFee = true;
    // $noShowFee is used if $noShowFeeIsCourseFee is false
    public $noShowFee = 20;
    // A discounted fee can be given
    public $noShowDiscountReason = A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;
    public $noShowDiscountedFee = 10;
    
    public $automatedReportRecipients = array('jonathan@appdevl.net', 'nate@growthsurveysystems.com', 'jeffrey.hofstetter@nsc.org');
    public function automatedReportFields($payment)
    {
        $registration = $payment->Enrollment;
        $student = $payment->Student;
        $course = $registration->Course;
        $return .= $student->email . ',' . $student->last_name . ','
                . $student->first_name . ',' . $course->course_start_date
                . ',' . $student->gender . ',' . $student->date_of_birth
                . ',' . $student->state . '
';
        return $return;
    }
    public function automatedReportQuery()
    {
        $yesterday = A25_Functions::stringToDate('Yesterday');
        $query = Doctrine_Query::create()
                ->select('*')
                ->from('A25_Record_Pay p')
                ->where('p.created > ?', $yesterday . ' 00:00:00')
                ->andWhere('p.created < ?', $yesterday . ' 23:59:59');
        return $query;
    }
    public $automatedReportTitle = 'Alive at 25 registration report';
    
    public function reasonForEnrollmentCourtOrderText()
    {
        return '<i>
  If you are taking this course because of an order or agreement with a court,
  prosecutor, or other criminal justice system organization, please select <b>Court
  Order or Pending Legal Matter</b> and then select the referring court/organization.
  &nbsp;<u>Criminal justice system certificates of completion appear differently than
  non-justice system related certificates</u>.</i>';
    }
    
    public $locationSeatAlertContact = 'Erasmo, Katie or John';
    public $allowCouponForCourtOrdered = false;
    public $collectLicenseStatus = true;
    public $onlyOneEnrollmentAllowed = true;
    public $showCanceledCoursesToPublic = false;
    
    public $courseInfoCertificateMessage = '<p><b>Proof of completion:</b><br/>
      Students will receive a certificate of completion from the instructor
      immediately following the successful completion of the course.</p>';
    
    public $courseInfoCertificateMessageVirtual = '<p><b>Proof of completion:</b><br/>
      Students will be mailed a certificate of completion within 5-7 business
      days following the successful completion of the course.</p>';
    
    public $courseInfoCertificateMessageSpanish = '<p><b>Comprobante de finalizaci&oacute;n:</b><br/>
      Los estudiantes recibir&aacute;n un certificado de finalizaci&oacute;n del
      instructor inmediatamente despu&eacute;s de completar con &eacute;xito el curso.</p>';
    
    public $courseInfoCertificateMessageVirtualSpanish = '<p><b>Comprobante de finalizaci&oacute;n:</b><br/>
      A los estudiantes se les enviar&aacute; por correo un certificado de
      finalizaci&oacute;n dentro de 5-7 d&iacute;as h&aacute;biles tras la
      finalizaci&oacute;n exitosa del curso.</p>';
    
    // Acceptable values to add are 'Driver License #' and 'Date of Birth'
    public $courseRosterFields = array('Student ID', 'Name', 'Reason For Attending', 'Status', 'Paid', 'Email', 'Phone', 'Age', 'Special Needs');
    // Only applicable if SMS plugin is used
    public $showSMSCourseRosterField = true;
    
    public function licenseStatuses()
    {
        $license_status = array();
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_unlicensed, 'I do not have a permit or license<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_drivingPermit, 'I have a driving permit<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_valid, 'I have a valid license<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_suspended, 'I have a suspended license<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_conditionalProbation, 'I have a license with conditional probation<br />');
        $license_status[] = mosHTML::makeOption(A25_Record_LicenseStatus::statusId_cancelled, 'I had a license but it has been canceled<br />');
        return $license_status;
    }
    
    // Only used in LicenseNo plugin
    public $confirmLicenseNo = false;
    
    // Only used in CourtDocketNumber plugin
    public $courtDocketNumberName = 'Court Docket Number';
    
    public $onlinePrerequisites = '';
    public $onlinePrerequisitesSpanish = '';
    public $onlineProviderImagePath = '/images/cspff_purple.png';
    public $copyInstructorReminderForVirtualClasses = array();
    public $hideCourseNotesForVirtualClasses = false;
    
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
            'How they heard' => $enroll->HearAboutType->hear_about_name
		);
    }
    
    public $googleAnalytics = '';
    
    public function privateCourseDescription($name)
    {
        return 'This particular training date is reserved for ' . $name . '.
If you do not belong to ' . $name . ', please
<a href="' . A25_Link::withoutSef('/find-a-course') . '">choose a
different class</a>.
</p><p>
To ensure that
only valid students enroll, this course has been assigned a registration code.
If you are affiliated with ' . $name . ', you should have received
the registration code already.  If you do not have the code, please contact ' .
$name . ', and they should be able to give it to you.';
    }
    
    public $allowCourtSurchargeWaive = true;
    
    public $twilioPhoneNumber = '7204662070';
    
    public $virtualCourseFee = 0;
    
    public $forbidFrontEndCourtEnrollments = false;
    
    public $adCode = '';
    
    public $allClassesVirtual = false;
    
    public $kahootLink = ''; //Only used by CA classes
    
    public $allowResendReminders = false;
    
    public $copyStudentEmailToSender = true;
    
    public $acceptedCards = 'Visa/Mastercard';
    
    public function cancellationTextOnAccountPage($course)
    {
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            return 'Si cancelas y ya has pagado, tu pago se aplicar&aacute;'
. ' autom&aacute;ticamente para inscribirte en otra clase. Sin embargo, si no'
. ' cancelas y no asistes, podr&iacute;as perder la oportunidad de reutilizar tu'
. ' pago en otra clase. ';
        }
        return 'If you cancel and have already paid, your payment will automatically be
      applied towards enrolling in a different class.
      However, if you do not cancel and do not attend, you may lose the opportunity
      to re-use your payment towards another class. ';
    }
    
    public $hasCertificates = true;
    
    // For online courses.  Only needs to be overwritten for testing environments 
    public $xapikey = 'C4pY6OALtzyrfHDFeIPzLXGvTs-q07ngJcaPSSSxdlC2';
    
    public $studentIdToStartNewPassword = 1;
    
    public $timezone = '';
    
    public $showWhenRegistrationCloses = false;
    
    public $createAccountComments = '';
}
