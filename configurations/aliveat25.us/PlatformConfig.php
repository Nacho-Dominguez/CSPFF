<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigAbstract.php');

class PlatformConfig extends PlatformConfigAbstract
{
	const isNationalPortal = true;

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
    }

	const phoneNumber = '(720) 269-4046';

    const businessHours = 'Monday-Thursday 8am-4pm<br />Friday 8am-1pm Mountain Time';

	public static function userCanEditCourseStatus()
	{
		return A25_DI::User()->isAdminOrHigher();
	}

    const reasonTypeId_PendingLegalMatter_number = 6;

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
