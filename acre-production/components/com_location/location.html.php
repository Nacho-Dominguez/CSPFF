<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

class HTML_location
{
	public static function progamInformation($taskname)
	{
		$aboutUs = new A25_InfoPage('aboutus','About Us',PlatformConfig::aboutUs());
        $whyThisWorks = new A25_InfoPage('whythisworks','Why This Works',PlatformConfig::whyThisWorks());
		$courses = new A25_InfoPage('courses','Courses',PlatformConfig::courses());
		$registrationGuidelines = new A25_InfoPage('registrationguidelines',
				'Registration Guidelines',PlatformConfig::registrationGuidelines());
		$paymentPolicies = new A25_InfoPage('paymentpolicies',
				'Tuition / Payment Policies',PlatformConfig::paymentInstructions());
		$supportUs = new A25_InfoPage('supportus',
				'Support Us',PlatformConfig::supportUs());
		$curriculum = new A25_InfoPage('curriculum',
				'Curriculum',PlatformConfig::curriculum());
		$evidence = new A25_InfoPage('evidence',
				'Evidence',PlatformConfig::evidence());
        $resources = new A25_InfoPage('resources', 'Resources',
                PlatformConfig::resources());
        $cert = new A25_InfoPage('certificate', 'Obtaining a SPAB Certificate',
                PlatformConfig::certificate());
        $whyNSC = new A25_InfoPage('whynsc', 'Why the NSC', PlatformConfig::whyNSC());
        $diversity = new A25_InfoPage('diversity', 'OUR Diversity, Equity, and Inclusion', PlatformConfig::diversity());

		$page = new A25_Page_ProgramInfo($taskname, $aboutUs, $whyThisWorks, $courses,
				$registrationGuidelines, $paymentPolicies, $supportUs,
                $curriculum, $evidence, $resources, $cert, $whyNSC, $diversity);
		$page->display();
	}
}
