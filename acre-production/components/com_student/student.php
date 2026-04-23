<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

require_once(dirname(__FILE__) . '/../../autoload.php');

global $task, $mainframe, $Itemid;

// Load the HTML_student class, from student.html.php.  front_html is a pre-
// defined key that points to the student.html.php file.
require_once( $mainframe->getPath( 'front_html' ) );
// AUTH_student class:
require_once( $mosConfig_absolute_path . '/administrator/components/com_student/student.auth.php' );

$action = trim( mosGetParam( $_POST, 'action', null ) );

$studentCookieName = mosHash( 'studentid'. A25_CookieMonster::sessionCookieName() );
$student_id = (int) mosGetParam( $_COOKIE, $studentCookieName, 0 );

$checkforward = false;
$msg = '';

switch( $action ) {
	case "login":
		$email = $database->getEscaped( mosGetParam( $_POST, 'email', NULL ) );
		$zip = $database->getEscaped( mosGetParam( $_POST, 'zip', NULL ) );
		$msg = login( $email, $zip );
		if (is_object($msg)) {
			throw new A25_Exception_DataConstraint($msg->_error);
		}
		$checkforward = true;
		break;

	case "register":
		$msg = register();
		$checkforward = true;
		break;

	default:
		break;
}

if ($checkforward) {
	$course_id = (int) mosGetParam( $_POST, 'course_id', 0 );
	$nexttask = trim( mosGetParam( $_POST, 'nexttask', 'account' ) );
	if ($course_id) {
		$redir = 'index.php?option=com_course&task=' . $nexttask . '&course_id=' . $course_id . '&Itemid=' . $Itemid;
	} else {
		$redir = 'account';
	}
	A25_DI::Redirector()->redirect($redir, $msg);
}

switch( $task ) {
	case "registerForm":
		$userid = trim( mosGetParam( $_POST, 'userid', null ) );
		$dob = trim( mosGetParam( $_POST, 'date_of_birth', null ) );
		$course_id = trim( mosGetParam( $_REQUEST, 'course_id', null ) );
		$nexttask = trim( mosGetParam( $_REQUEST, 'nexttask', null ) );
		registerForm( $userid, $dob, $course_id, $nexttask );
		break;

	case "loginForm":
        if (PlatformConfig::isNationalPortal) {
            A25_DI::Redirector()->redirectBasedOnSiteRoot('/', 'Choose your state below');
        }
		$course_id = trim( mosGetParam( $_REQUEST, 'course_id', null ) );
		$nexttask = trim( mosGetParam( $_REQUEST, 'nexttask', null ) );
		loginForm( $course_id, $nexttask );
		break;

	case "profile":
	default:
		A25_DI::Redirector()->redirectBasedOnSiteRoot('/account', $_GET['mosmsg']);
		break;

  /**
   * @todo-jon-low-small - this case can be simplified without so many
   * intermediate classes and functions, as the only thing that really matters
   * is $enroll->cancelEnrollment(), which is buried under several function
   * calls.
   */
	case "cancelenrollment":
		$enroll = A25_Record_Enroll::retrieve($xref_id);
		if ($enroll->Course->isPastCancellationDeadline()) {
			$msg = 'You are not allowed to cancel because course is past cancellation deadline.';
		} else {
			$msg = cancelEnrollment( $xref_id );
		}

		$redir = A25_Link::to('/account');
    	A25_DI::Redirector()->redirect($redir, $msg);
		break;

	case "logout":
		logout();
		break;
}

/**
 * Shows student log in form
 *
 * @param integer $course_id
 * @param string $nexttask
 * @return void
 *
 * @author Christiaan van Woudenberg
 * @version June 30, 2006
 */
function loginForm( $course_id, $nexttask ) {
	global $Itemid;

	A25_OldCom_Student_LoginForm::run($course_id, $nexttask, $Itemid);
}

/**
 * Shows student register form
 *
 * @param integer $course_id
 * @param string $nexttask
 * @return void
 *
 * @author Christiaan van Woudenberg
 * @author Thomas Albright
 * @version December 14, 2007
 */
function registerForm( $userid, $dob, $course_id, $nexttask = 'account' ) {
	global $Itemid;

	A25_OldCom_Student_RegisterForm::run($userid, $dob, $course_id,
			$Itemid, $nexttask );
}

/**
 * Log in
 *
 * @param string $email
 * @param string $zip
 * @return void
 *
 * @author Christiaan van Woudenberg
 * @version July 2, 2006
 */
function login( $email, $zip ) {

	return A25_OldCom_Student_Login::run($email, $zip);
}


/**
 * Log out
 *
 * @return void
 *
 * @author Christiaan van Woudenberg
 * @version July 28, 2006
 */
function logout( ) {
	A25_OldCom_Student_Logout::run();
}


/**
 * Registers a student
 *
 * @param string $redir
 * @return void
 *
 * @author Christiaan van Woudenberg
 * @version July 5, 2006
 */
function register() {
	global $mosConfig_offset, $mosConfig_mailfrom, $mosConfig_fromname;

	return A25_OldCom_Student_Register::run($mosConfig_offset,
			$mosConfig_mailfrom, $mosConfig_fromname);
}

/**
 * Cancels an existing enrollment.  If there is an order, it is cancelled.
 *
 * @param int $xref_id
 * @return string - message of success or failure
 */
function cancelEnrollment($xref_id) {
	return A25_OldCom_Student_CancelEnrollment::run($xref_id);
}
?>
