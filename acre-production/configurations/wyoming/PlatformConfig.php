<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '6Y9h4JpLf';
    const AUTHORIZE_NET_TRAN_KEY = '5M47ahj86Xk96JNc';
    const STATE_NAME = 'Wyoming';
    const STATE_ABBREV = 'wy';
    const displayPriceOnCertificate = false;
    const certPrinter = 'cert_pdf.php';
    const defaultCourtFee = 35;

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;

    public $kickOutAfterDeadline = '1 hour';

    const minAge = 14;
    const maxAge = 24;
    const courseDuration = '04:30';

    const phoneNumber = '(307) 473-3234 in the Casper area, (307) 352-3100 in the Rock Springs/Evanston area, (307)777-3843 in Cheyenne and statewide';
    const faxNumber = '(307) 777-4282';
    const businessHours = 'Monday-Friday, 8:30am-4:30pm, Mountain Time';
    const mailingAddressName = 'Alive At 25 Program';

    const messageSenderId = 489;

    public static function paymentAddressHtml()
    {
        return self::administrativeAddressHtml();
    }
    public static function administrativeAddressHtml()
    {
        return 'Wyoming State Patrol<br/>'
            . 'Safety and Training Division<br/>'
            . self::mailingAddressName . '<br/>'
            . '5300 Bishop Blvd.<br/>'
            . 'Cheyenne, WY 82009';
    }
    const agency = 'Alive at 25 Program';

    /**
     * This is similar to the normal contactUs(), but without "Payment
     * Address, since Wyoming doesn't take payments.
     */
    public static function contactUs()
    {
        return '
    <div class="row">
      <div class="col-sm-4">
        <p><strong>Administrative Office Address</strong></p>
        <p>
        ' . PlatformConfig::administrativeAddressHtml() . '
        </p>
      </div>
      <div class="col-sm-4">
        <p><img src="https://aliveat25.us/images/phone.png" alt="envelope.png" style="margin: 2px;"/><strong>Phone</strong></p>
        <p>
        Casper area: (307) 473-3234<br />
        Rock Springs/Evanston area: (307) 352-3100<br />
        Cheyenne and statewide: (307) 777-3843
        </p>
        <p>
        Fax: ' . PlatformConfig::faxNumber . '
        </p>
        <p><img src="https://aliveat25.us/images/envelope.png" alt="envelope.png" style="margin: 2px;"/><a href="mailto:' . A25_DI::PlatformConfig()->contactEmailAddress
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
    }
    //If this is changed, the address in /etc/mail/aliases needs to be changed too
    public $contactEmailAddress = 'benjamin.kiel@wyo.gov?cc=calla.power1@wyo.gov';
    public $sendFromEmail = 'wyoming@coloradosafedriver.org';

    const reasonTypeId_PendingLegalMatter_number = 9;

    public function tuitionDetails($tuition)
    {
        return '<p>Tuition is ' . $tuition . ' for students taking the course voluntarily.</p>
      <p>Tuition is $' . PlatformConfig::defaultCourtFee . ' for court-ordered students</p>';
    }

    public function findACourseComments()
    {
        return
        '<p>Tuition is free for students taking the class voluntarily, and $35 for court-ordered students.</p>';
    }

    public $paymentForm = 'lnps-form';
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-LJC24N7F1W\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LJC24N7F1W');
</script>";
    
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
