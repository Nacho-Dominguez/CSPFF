<?php

defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

A25_DoctrineRecord::$disableSave = true;

require_once( dirname(__FILE__) . '/location.html.php' );

ComLocation::run();

class ComLocation
{
	public static function run()
	{
		global $task;

		switch($task) {
			case "findcourse":
				self::runSelector($task,
						PlatformConfig::findACoursePath,
						'Find a Course');
				break;
			case "contactstate":
				self::runSelector($task,
						PlatformConfig::contactPath,
						'Contact Us - Select State Office');
				break;
			case "myaccount":
				self::runSelector($task,
						'/account',
						'Access Your Account');
				break;
			case "programinfo":
				$prepend = '<h1>What is the ALIVE AT 25 Curriculum?</h1>
					<p>Although the National Safety Council-designed Alive at 25
					curriculum is consistently the same throughout the nation,
					each state has its own Program which administers Alive at
					25. Law enforcement agencies in each of these states are
					collaborating to provide a network of programs to reach a
					wider audience. In these sections, you\'ll find information
					specific to the Alive at 25 Program in your state, including
					tuition rates. Please click on a state name below for
					specific Program information.</p>';
				self::runSelector($task,
						PlatformConfig::programInfoPath,
						'Program Information', $prepend);
				break;
			case "faq":
				self::runSelector($task,
						PlatformConfig::faqPath,
						'FAQ');
				break;

			case "contactus":
        echo PlatformConfig::openPlatformTemplate('contactUs.phtml');
				break;

			case "privacypolicy":
				echo PlatformConfig::openPlatformTemplate('privacyPolicy.phtml');
				break;

			case "becomeaninstructor":
				echo PlatformConfig::openPlatformTemplate('becomeAnInstructor.phtml');
				break;

			case "aboutus":
            case "whythisworks":
            case "courses":
			case "registrationguidelines":
			case "paymentpolicies":
			case "supportus":
            case "curriculum":
            case "evidence":
            case "resources":
            case "certificate":
            case "whynsc":
            case "diversity":
				HTML_location::progamInformation($task);
				break;

			default:
				HTML_location::progamInformation('aboutus');
				break;
		}
	}
	private static function runSelector($task, $forwardPath, $heading,
			$prepend = '')
	{
		$selector = new A25_Page_StateSelector($_GET['state'],
				"component/option,com_location/task,$task/state,",
				$forwardPath);
		echo '<div style="margin: 15px; border: 1px solid #769E3B">';
		echo '<div class="content">';
		echo "<div class='colHeader'>$heading</div>";
		echo '<div id="colContent" style="font-size: 12px;">';
		echo $prepend;
		$selector->forwardOrList();
		echo '</div></div></div>';
	}
}

?>
