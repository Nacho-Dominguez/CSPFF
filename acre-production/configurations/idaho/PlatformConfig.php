<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
	const AUTHORIZE_NET_LOGIN = 'ant227953893';
	const AUTHORIZE_NET_TRAN_KEY = 'fNxuM0TfeyZtycNp';

	const STATE_NAME = 'Idaho';
  const STATE_ABBREV = 'id';
	const displayPriceOnCertificate = false;
	const certPrinter = 'cert_pdf.php';

	const agency = 'Idaho Office of Highway Safety';
	const phoneNumber = '(208) 334-8104';
	const faxNumber = '(208) 334-4430';
	const businessHours = 'Monday-Friday, 7am-4pm, Mountain Time';
	const mailingAddressName = 'Alive At 25 Program';

	const allowCourtReferrals = false;
	const defaultCourtFee = 0;
	const courseDuration = '04:30';

	const minAge = 14;

	const messageSenderId = 556;

	static public function administrativeAddressHtml()
	{
		return self::mailingAddressName . '<br/>'
			. 'P.O. Box 7129<br/>'
			. 'Boise, ID 83707-1129';
	}
	static public function paymentAddressHtml()
	{
		return self::administrativeAddressHtml();
	}

	/**
	 * Idaho doesn't take payments
	 */
	public static function paymentInstructions()
	{
		return '';
	}

	/**
	 * This is similar to the normal contactUs(), but without "Payment
	 * Address, since Idaho doesn't take payments.
	 */
	public static function contactUs()
	{
    return '
    <p>Please note that replacement certificates can only be mailed Monday-Wednesday</p>
    <div class="row">
      <div class="col-sm-4">
        <p><strong>Administrative Office Address</strong></p>
        <p>
        ' . PlatformConfig::administrativeAddressHtml() . '
        </p>
      </div>
      <div class="col-sm-4">
        <p><img src="https://aliveat25.us/images/phone.png" alt="phone.png" style="margin: 2px;"/><strong>Phone</strong></p>
        <p>
        Ph: ' . PlatformConfig::phoneNumber . '<br />
        Fax: ' . PlatformConfig::faxNumber . '
        </p>
        <p><img src="https://aliveat25.us/images/envelope.png" alt="envelope.png" style="margin: 2px;"/><a href="mailto:' . ServerConfig::adminEmailAddress . '">Email Us</a></p>
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
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=G-LJC24N7F1W\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-LJC24N7F1W');
</script>";
    
    public $sendFromEmail = 'idaho@coloradosafedriver.org';
    
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
    
    public $twilioPhoneNumber = '7204667088';
}
