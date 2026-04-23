<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    const defaultCourtFee = 119;
    const defaultCourtSurcharge = 25;
    public $acceptChecks = false;

    const courseDuration = '06:00';

    const hideCourtInfoOnNonPublicCerts = true;

    const courseTitle = 'Attitudinal Dynamics of Driving';
    const siteTitle = 'Attitudinal Dynamics of Driving - Colorado';
    const mailingAddressName = 'Attitudinal Dynamics of Driving';
    const businessHours = 'Monday-Thursday 8am-4pm<br />Friday 8am-1pm Mountain Time';

    // This setting is necessary for ADOD so that the yellow 'selected state'
    // box is hidden
    const isAState = false;

    const minAge = 0;
    const maxAge = 100;

    public $kickOutBeforeDeadline = '7 days';

    const STATE_NAME = "Colorado";
    const STATE_ABBREV = 'co';

    const instructorClassCreationDeadline = 30;
    const allowInstructorsToEditCourseCapacity = false;

    const rowForBigTextMiddleOnCertificate = 97;

    const messageSenderId = 380;

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
            array(ServerConfig::httpUrlWithoutSlash() . '/content/view/2/4/', 4,
                'Links'),
            array(ServerConfig::httpUrlWithoutSlash() . '/content/view/3/5/', 5,
                'Partners'));
    }

    const courseTitleFull = PlatformConfig::courseTitle;
    const prettyLandingPageUrl = 'adod.coloradosafedriver.com';
    const emailLogoPath = 'images/adod_full_logo.jpg';

    public function accountUrlDirect()
    {
        return PlatformConfigAbstract::accountUrl();
    }

    public static function creditCardRequirementMessage()
    {
        return 'I understand that all payments are NON-REFUNDABLE and cannot be '
        . 'transferred to any other student account. If I am unable to attend, '
        . 'and cancel 24 hours in advance of the course start time, my payment '
        . 'can be used for another course within 1 year of this payment.';
    }

    public static function userCanEditCourseStatus()
    {
        return A25_DI::User()->isAdminOrHigher();
    }

    public function findACourseComments()
    {
        return
        '<p>ZOOM VIRTUAL CLASSES: <b>These courses will be offered as a remote option and will be done using Zoom. Students taking this class must have a computer with BOTH Audio and Video Capabilities.  <span style="color: red">A $'
        . $this->virtualCourseFee . ' fee will be added to all Zoom classes.</span></b></p>
<p><u>Students enrolling in a virtual ADoD course are agreeing to the following:</u>
<ol>
<li>Students MUST provide a reliable <b>email</b> address as this is how meeting links will be sent out</li>
<li>Students MUST provide a valid <b>mailing address</b> as course material will be mailed out to each student.</li>
<li>Students MUST have a reliable internet connection. If students lose their internet connection, for more than 5 minutes, they will be <b>REQUIRED</b> to take the class over.</li>
<li>Students enrolling in a virtual ADoD class understand their course completion certificates will be mailed to them within 5 business days following the completion of their course.  <u>Do NOT enroll into a class that is within 2 Weeks of your court date, as the certificate of completion will not be delivered in time.</u></li>
</ol>
<b>BY REGISTERING, YOU ARE CERTIFYING YOU UNDERSTAND AND WILL FOLLOW ALL GUIDELINES DURING THE CLASS.</b></p>
<p>
Classes occurring within 2 weeks require immediate payment with credit card in
order to enroll.  These are marked with an \'<span class="asterisk">*</span>\'.
</p>
<p>
Classes outside of 2 weeks have the option to be paid with money order or credit
card, as long as the payment is received within ' . $this->kickOutBeforeDeadline .
        ' of enrolling in the course.</p>';
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

    public function courseCommentsDescription()
    {
        return 'Use this field to inform the DDC staff if you experienced a unique'
        . ' situation in your class.  Example: Student(s) asked a controversial'
        . ' question or used a problematic scenario during role play.';
    }

    public $contactUsTitle = 'Contact Us';

    public $sendClassReminder = true;
    
    public function marijuanaPolicyLink()
    {
        return A25_Link::to('content/view/4');
    }
    public $showCanceledCoursesToPublic = true;
    
    public function courseCommentsPrepend()
    {
        return '<p>Please <b>bring a photo ID</b> (if available). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.</p>
';
    }
    
    public $copyInstructorReminderForVirtualClasses = array('john@cspff.net', ServerConfig::adminEmailAddress);
    public $hideCourseNotesForVirtualClasses = true;
    public $virtualCourseFee = 10;
}
