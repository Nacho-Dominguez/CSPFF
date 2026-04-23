<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '89KJa6Ms4nq';
    const AUTHORIZE_NET_TRAN_KEY = '93Jd9eU8uN7aN3Q5';

    public $acceptChecks = false;
    public $requireEmail = true;

    const STATE_NAME = 'Maine';
    const STATE_ABBREV = 'maine';
    const displayPriceOnCertificate = false;

    const defaultCourtFee = 60;

    const agency = 'National Safety Council of Northern New England';
    const shortAgency = 'National Safety Council of NNE';
    const phoneNumber = '888-396-2047';
    const faxNumber = '888-396-2048';
    const businessHours = 'Monday-Friday, 8:00am-4:00pm, Eastern Time';
    public $paymentTo = 'NSCNNE';

    const minAge = 15;
    const maxAge = 24;

    const messageSenderId = 383;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_New();
    }

    public $kickOutBeforeDeadline = '7 days';
    public $kickOutAfterDeadline = '59 minutes';  // Setting this to 1 hour causes the payment timer to rollover for the first couple seconds

    public static function administrativeAddressHtml()
    {
        return self::agency . '<br/>'
        . '405 Western Ave. #317<br/>'
        . 'South Portland, ME 04116';
    }
    public static function paymentAddressHtml()
    {
        return self::agency . '<br/>'
        . '2 Whitney Road, Suite 11<br/>'
        . 'Concord, NH 03301';
    }

    public function findACourseComments()
    {
        return
        '
<p style="font-weight: bold; font-size: 16px;">
ONLINE, LIVE WEBINAR-STYLE CLASSES
</p><p>
For live interactive virtual classes, a student workbook will be mailed to the
address you provide when you register. Please be sure to submit your correct
mailing address. A ZOOM login link will be e-mailed to you a few days prior to
the class date. Your certificate of completion will be mailed to you after you
have completed the course. Students are responsible for submitting the certificate to the DMV.
</p><p>
All classes are $60. Classes are usually held on Saturdays and Sundays. This class does not qualify for points reduction.
</p><p>
PLEASE NOTE: A webcam is mandatory for this class as your visual presence is
required. Cell phones are not recommended for use in attending this class. Cell
phone Audio / Video is unreliable and calls are regularly dropped.
</p><p>
If you need to speak with someone from our office, you can
do so by phone (207-854-8441) or
<a href="mailto:aliveat25@nscnne.org" target="blank">email</a>.
</p>';
    }

    const reasonTypeId_PendingLegalMatter_number = 6;

//  Temporarily removed for virtual classes
    public function courseCommentsPrepend()
    {
//        return '<p>Please be sure to arrive early, as <em>late arrivals are not allowed to attend</em>.</p>';
        return '';
    }
    
    public $courseInfoCertificateMessageVirtual = '<p><b>Proof of completion:</b><br/>
        Students will be mailed a certificate of completion immediately following
        the successful completion of the course and should receive it within 3-5 business days.</p>';

    const reasonTypeId_Speeding = 7;
    const reasonTypeId_Headlights = 8;
    const reasonTypeId_Accident = 9;
    const reasonTypeId_CellPhone = 10;
    const reasonTypeId_OtherCitation = 11;
    const reasonTypeId_Endanger = 13;
    const reasonTypeId_Drinking = 14;
    public $courtOrderedReasonTypeList = array(
    A25_Record_ReasonType::reasonTypeId_CourtOrdered,
    self::reasonTypeId_Speeding,
    self::reasonTypeId_Headlights,
    self::reasonTypeId_Accident,
    self::reasonTypeId_CellPhone,
    self::reasonTypeId_OtherCitation,
    self::reasonTypeId_Endanger,
    self::reasonTypeId_Drinking
    );

    const noShowsBeforeNoShowFee = 3;
    public $sendClassReminder = true;
    
    public $hideCourseNotesForVirtualClasses = true;
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-LJC24N7F1W\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LJC24N7F1W');
</script>";
    
    public $sendFromEmail = 'nscnne@coloradosafedriver.org';
    
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
