<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '54Y3Bswj7Cc';
    const AUTHORIZE_NET_TRAN_KEY = '6u23Pqp7NgQ476e9';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = true;

  // This setting is necessary so that the yellow 'selected state' box is
  // hidden
    const isAState = false;
    const STATE_NAME = "California";
    const STATE_ABBREV = 'ca';
    
    const agency = 'California Safe Driver';
    const phoneNumber = '1-877-525-DRIVE (3748)';
    const faxNumber = null;
    const businessHours = 'Monday-Friday 9am-5pm Pacific Time';

    public function colorScheme()
    {
        return new A25_ColorScheme_SPABOrange();
    }

    const siteTitle = 'California Safe Driver';

    const messageSenderId = 63;

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
    public $paymentTo = 'California Safe Driver';

    public $sendReminders = false;
    
    public $contactUsTitle = 'Contact Our Administrative Office';
    
    public function siteTemplateHeader()
    {
        return new Acre\A25\Template\NoAccountHeader(
            new Acre\A25\Template\NoTopMenuContainer(
                new Acre\A25\Template\StandardTopMenu()
            )
        );
    }

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::programInfoUrl(), 42, 'Program Information'));
    }
    public function findACourseUrl()
    {
        return "";
    }
    
    public $onlineProviderImagePath = '/images/cobert.png';
    
    const displayBecomeInstructor = false;
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-11245966378\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11245966378');
</script>";
    
    public $sendFromEmail = 'california@coloradosafedriver.org';

    public static function contactUs()
    {
        $return = '
		<div class="row">
			<div class="col-sm-3">
				<p><strong>Mailing Address</strong></p>
				<p>
				' . PlatformConfig::paymentAddressHtml() . '
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
    
    public $studentIdToStartNewPassword = 90082;
}
