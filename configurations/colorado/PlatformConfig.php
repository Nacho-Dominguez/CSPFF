<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

// Changes made to this file should likely be made to the aliveat25.us PlatformConfig
// file as well, since instructors tend to forget the /co after aliveat25.us.

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    const linkToTuitionRates = true;

    const STATE_NAME = 'Colorado';
    const STATE_ABBREV = 'co';
    const displayPriceOnCertificate = true;
    const certPrinter = 'cert_pdf.php';

    public $acceptChecks = false;

    const courseDuration = '04:30';

    const minAge = 14;

    public $kickOutBeforeDeadline = '7 days';

    const defaultCourtFee = 89;
    const defaultCourtSurcharge = 25;
    const instructorClassCreationDeadline = 30;
    const allowInstructorsToEditCourseCapacity = false;
    const discountedDrivingPermitTuition = 39;

    const messageSenderId = 494;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_New();
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
                'Shop Our Store'),
            array(A25_Link::to(
            'images/resources/Law Handout - revised 3.2017.pdf'
            ),
                10, 'Laws For Drivers'));
    }

    const phoneNumber = '(720) 269-4046';

    const businessHours = 'Monday-Thursday 8am-4pm<br />Friday 8am-1pm Mountain Time';

    public static function creditCardRequirementMessage()
    {
        return 'I understand that all payments are NON-REFUNDABLE and cannot be '
        . 'transferred to any other student account. If I am unable to attend, '
        . 'and cancel 24 hours in advance of the course start time, my payment '
        . 'can be used for another course within 1 year of this payment.<br/>If '
        . 'I do not cancel and do not attend, a no-show fee of $20 for standard '
        . 'tuition or $10 for discounted tuition will be charged.';
    }
    public static function userCanEditCourseStatus()
    {
        return A25_DI::User()->isAdminOrHigher();
    }
    public function findACourseComments()
    // Changes here may also need to be made to Controller/Broomfield.phtml
    {
        return
        '<p>ZOOM VIRTUAL CLASSES: <b>These courses will be offered as a remote option and will be done using Zoom. Students taking this class must have a computer with BOTH Audio and Video Capabilities.  <span style="color: red">A $'
        . $this->virtualCourseFee . ' fee will be added to all Zoom classes.</span></b></p>
<p><u>Students enrolling in a virtual Alive at 25 course are agreeing to the following:</u>
<ol>
<li>Students MUST provide a reliable <b>email</b> address as this is how meeting links will be sent out</li>
<li>Students MUST provide a valid <b>mailing address</b> as course material will be mailed out to each student.</li>
<li>Students MUST have a reliable internet connection. If students lose their internet connection, for more than 5 minutes, they will be <b>REQUIRED</b> to take the class over.</li>
<li>Students enrolling in a virtual Alive at 25 class understand their course completion certificates will be mailed to them within 5 business days following the completion of their course.  <u>Do NOT enroll into a class that is within 2 Weeks of your court date, as the certificate of completion will not be delivered in time.</u></li>
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

    public function tuitionDetails($tuition)
    {
        return '<p>Tuition is $' . PlatformConfig::discountedDrivingPermitTuition . ' for students taking the course to obtain a driving permit.</p>
        <p>Tuition is ' . $tuition . ' for all other students</p>
        <p>An additional $' . $this->virtualCourseFee . ' fee applies to Zoom courses</p>';
    }

    public function courseCommentsDescription()
    {
        return 'Use this field to inform the DDC staff if you experienced a unique'
        . ' situation in your class.  Example: Student(s) asked a controversial'
        . ' question or used a problematic scenario during role play.';
    }
    public $sendClassReminder = true;
    
    public function marijuanaPolicyLink()
    {
        return A25_Link::to('content/view/42');
    }
    
    const noShowsBeforeNoShowFee = 1;
    public $noShowFeeIsCourseFee = false;
    public $noShowFee = 20;
    public $noShowDiscountReason = A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;
    public $noShowDiscountedFee = 10;
    public $showCanceledCoursesToPublic = true;
    
    public function courseCommentsPrepend()
    {
        return '<p>Please <b>bring a photo ID</b> (if available). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.</p>
';
    }
    public $copyInstructorReminderForVirtualClasses = array('john@cspff.net', ServerConfig::adminEmailAddress);
    public $hideCourseNotesForVirtualClasses = true;
    
    const firstInstructorReminderHoursBefore = 360;    // 15 days
    const secondInstructorReminderHoursBefore = 168;    // 7 days
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-LJC24N7F1W\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LJC24N7F1W');
</script>";
    
    public $virtualCourseFee = 10;
    
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
}
