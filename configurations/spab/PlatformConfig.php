<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '5G2gV3cWWXmK';
    const AUTHORIZE_NET_TRAN_KEY = '95bCt87n878mFzKG';

    public $acceptChecks = false;
    public $acceptOnlyCreditCards = false;
    public $acceptCreditCards = false;

    const STATE_NAME = 'California';
    const STATE_ABBREV = 'ca';
    const courseTitle = 'School Pupil Activity Bus';
    const siteTitle = 'SPAB Resources';
    const mailingAddressName = 'SPAB Resources';

    // This setting is necessary for ADOD so that the yellow 'selected state'
    // box is hidden
    const isAState = false;

    const displayPriceOnCertificate = false;

    const defaultCourtFee = 425;
    const courseDuration = '07:00';

    const agency = 'California Safe Driver';
    const phoneNumber = '833-TO1-SPAB (861-7722)';
    const faxNumber = null;
    const businessHours = 'Monday-Friday 9am-5pm Pacific Time';

    const minAge = 0;
    const maxAge = 100;

    const displayBecomeInstructor = false;
    const allowInstructorsToSeeCourseRevenue = false;

    const turnOnRequestSupplies = false;
    const turnOnInstructorTimesheet = false;

    const rowForBigTextMiddleOnCertificate = 97;

    const messageSenderId = 2;

    public function certPdfSettings()
    {
        return new A25_CertPdfSettings_Adod();
    }

    public function colorScheme()
    {
        return new A25_ColorScheme_SPABOrange();
    }

    public function topMenu()
    {
        return array(array(ServerConfig::staticHttpUrl(), 1, 'Home'),
            array(PlatformConfig::findACourseUrl(), 19, 'Find A Course'),
            array(PlatformConfig::programInfoUrl(), 42, 'Program Information'));
    }

    const courseTitleFull = PlatformConfig::courseTitle;
    const prettyLandingPageUrl = 'SPABresources.com';
    const emailLogoPath = 'images/adod_full_logo.jpg';

    public function accountUrlDirect()
    {
        return PlatformConfigAbstract::accountUrl();
    }

    public static function creditCardRequirementMessage()
    {
        return '<p>I understand that I am paying for services provided by, and
that my credit card statement will show a charge from, <i>'
. PlatformConfig::agency . ' ' . PlatformConfig::courseTitle . '</i>.</p>
<p>I acknowledge that I have read and accept the registration guidelines,
including but not limited to:
<ul><li>No late students will be allowed to attend class</li>
<li>Students who are late or miss a class sacrifice their tuition</li>
<li>Students must complete the entire course, actively participate, and follow
rules set by the instructor</li>
<li>We do not offer refunds, but you may change your class reservation if you
cancel at least 48 hours in advance</li></ul></p>
<p>I understand that this course is not considered by the California Department
of Motor Vehicles to be a point reduction course, nor does it guarantee that my
insurance company will provide me a reduction in my insurance premiums.';
    }

    /**
     * California counts no-shows in course revenue, since they don't ever give
     * credits to no-shows.  The next 2 declarations allow for that in course
     * revenue calculations.
     */
    public static function enrollmentStatusesNotElligibleForCourseRevenue()
    {
        // Get all parent statuses
        $statuses = parent::enrollmentStatusesNotElligibleForCourseRevenue();

        // Remove no show status if in statuses
        $noShowIndex = array_search(A25_Record_Enroll::statusId_noShow, $statuses);
        if ($noShowIndex !== false) {
            // Remove noShow value
            unset($statuses[$noShowIndex]);
            // Reorder array
            $statuses = array_values($statuses);
        }

        // Add Registered, since they don't want unpaid enrollments included
        array_push($statuses, A25_Record_Enroll::statusId_registered);

        return $statuses;
    }

    public static function orderItemTypesElligibleForCourseRevenue()
    {
        $types = parent::orderItemTypesElligibleForCourseRevenue();
        $types[] = A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows;
        return $types;
    }

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

    public function findACourseComments()
    {
        return '<p style="font-size: 15px; color: #333;">Originals sign up for all 3 modules (original training plus renewal training parts 1 & 2) ($1275), Renewals sign up for renewal modules 1 & 2 ($850)<p>';
    }

    const noShowsBeforeNoShowFee = 1;
    public $paymentTo = 'California Safe Driver';
    public function kickOutInterfaces()
    {
        return array();
    }
    const classReminderMinHoursBefore = 12;
    const classReminderMaxHoursBefore = 24;
    public $sendClassReminder = true;

    public function cancellationPolicyForEmail()
    {
        return 'If you are unable to attend this class, please <a href="'
        . PlatformConfig::accountUrlDirect() . '">cancel or
reschedule</a> your class as soon as possible or at least 48 hours in
advance.
';
    }
    
    public function displayTuitionOnCourseInfo()
    {
        return false;
    }
    
    public function marijuanaPolicyLink()
    {
        return A25_Link::to('content/view/2');
    }

    public function courseCommentsPrepend()
    {
        return '<p>Please <b>bring a photo ID</b> (REQUIRED). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.</p>
';
    }
    // Empty because there is no courses page
    public static function courses()
    {
    }

    public function siteTemplateHeader()
    {
        return new Acre\A25\Template\AliveAt25UsHeader(
            new Acre\A25\Template\StandardTopMenuContainer(
                new Acre\A25\Template\StandardTopMenu()
            )
        );
    }
    
    public $evaluationTemplate = 'SPABCourseEvaluationTemplate.pdf';
    
    public static function loginEnrollMakeDuplicateAccountWarningText()
    {
        return '<p class="required">Note: Each student needs their own individual account. (i.e. co-workers need separate accounts)</p>';
    }
    public $collectLicenseStatus = false;
    public $onlyOneEnrollmentAllowed = false;
    
    public $contactUsTitle =
        'Contact Us';

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
				<p><strong>Phone</strong></p>
				<p>
				Ph: ' . PlatformConfig::phoneNumber;

        if (PlatformConfig::faxNumber) {
            $return .= '<br />Fax: ' . PlatformConfig::faxNumber;
        }

        $return .= '
				</p>
                <p><a href="mailto:'
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
    
    public function certificatePrinter()
    {
        return new A25_Printing_SPABCert();
    }
    
    public $googleAnalytics = "<!-- Google tag (gtag.js) -->
<script async src=\"https://www.googletagmanager.com/gtag/js?id=AW-11280801881\"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11280801881');
</script>";
    
    public $sendFromEmail = 'spab@coloradosafedriver.org';
    
    public $copyStudentEmailToSender = false;
}
