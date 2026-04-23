<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

global $acl, $mainframe, $option;

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_course' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage courses.' );
}

/**
 * Class ADMIN_HTML_course
 */
require_once( $mainframe->getPath( 'admin_html' ) );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage any courses.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );
$cid = mosGetParam( $_REQUEST, 'cid', array( 0 ) );
$id = mosGetParam( $_REQUEST, 'id', 0 );

switch ($task) {
	case 'view':
		A25_OldCom_Admin_ViewCourse::run( intval( $cid[0] ), $option );
		break;

	case 'viewA':
		A25_OldCom_Admin_ViewCourse::run( $id, $option );
		break;

	case 'viewRoster':
		viewRoster( $id, $option );
		break;

	case 'cancelform':
		cancelForm( $id, $option );
		break;

	case 'cancelcourse':
		$course_id = (int) mosGetParam( $_POST, 'course_id', 0 );
		cancelCourse($course_id);
		break;

	case 'applyenroll':
		if (checkPerms($_POST['location_id'], $task)) {
			$course_id = (int) mosGetParam( $_POST, 'course_id', 0 );
			saveEnroll( $course_id );
		} else {
			throw new A25_Exception_IllegalAction('You do not have '
				. 'permission to apply enrollment changes for courses at this '
				. 'location.');
		}
		break;

	case "publish":
		publishCourse( $cid, 1 );
		break;

	case "unpublish":
		publishCourse( $cid, 0 );
		break;

	case "newmsg":
		newMsg( $id, $option );
		break;

	case "sendmsg":
		$course_id = (int) mosGetParam( $_POST, 'course_id', 0 );
		sendMsg($course_id);
		break;

	case "list":
	default:
    A25_DI::Redirector()->redirect('/list-courses','',301);
    /**
     * @todo-jon-medium-small - verify that listCourse() and everything it calls
     * on down the tree is un-used, and delete it.
     */
		listCourse( $option );
		break;
}

/**
 * Lists all courses in alphabetical order, with the option to filter by state.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function listCourse( $option ) {
	global $mainframe, $mosConfig_list_limit, $locs;

    A25_OldCom_Admin_ListCourse::run( $mainframe, $mosConfig_list_limit, $locs, $option );
}


/**
 * View roster for printing an individual course
 * @author Garey Hoffman
 *
 *  @version October 20, 2006
 *
 * @param integer $course_id
 * @param  string $option
 * @return void
 */
function viewRoster( $course_id, $option='com_course' ) {

	A25_OldCom_Admin_ViewRoster::run( $course_id, $option='com_course' );
}

/**
 * Edit information for an individual course, or add a new course.
 *
 * @param integer $course_id
 * @param  string $option
 * @return void
 */
function editCourse($course_id='0', $option='com_course') {
	global $my, $locs, $acl;

	if (!A25_OldCom_Admin_EditCourse::run($my, $locs, $acl, $course_id, $option))
		exit();
}

/**
 * Saves enrollment information
 * 
 * @param  string $task
 */
function saveEnroll( $course_id ) {

	A25_OldCom_Admin_SaveEnroll::run($course_id);
}


/**
 * Publishes or unpublishes one or more courses.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
function publishCourse( $cid, $publish=1 ) {
	global $database, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		throw new A25_Exception_DataConstraint('Select an item to '
				. $action);
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__course"
	. "\n SET published = " . intval( $publish )
	. "\n WHERE course_id IN ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		throw new A25_Exception_DataConstraint(
				$database->getErrorMsg());
	}
	
	A25_DI::Redirector()->redirect( 'index2.php?option=com_course&action=list' );
}

/**
 * Shows form for creating a new course message.
 * @author Christiaan van Woudenberg
 * @version July 23, 2006
 *
 * @param $id
 * @param $option
 * @return void
 */
function newMsg( $id, $option ) {
	global $my;

	A25_OldCom_Admin_NewMessage::run( $id, $my );
}


/**
 * Sends messages to enrolled students in a course.  Does not send to Canceled
 * students.
 *
 * @return void
 */
function sendMsg($course_id)
{
	$course = A25_Record_Course::retrieve($course_id);
	$count = $course->emailStudents($_POST['subject'], $_POST['message']);

	$msg = 'Sent course message to ' . $count . ' students.';
	A25_DI::Redirector()->redirect('index2.php?option=com_course&task=viewA&id='
			. $course_id, $msg);
}


/**
 * Shows form for cancelling a course and creating a new course message.
 * @author Christiaan van Woudenberg
 * @version August 12, 2006
 *
 * @param $id
 * @param $option
 * @return void
 */
function cancelForm( $id, $option ) {
	global $database, $my;

	$course = A25_Record_Course::retrieve( $id );
	if ($course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_completed) > 0
			||$course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_pending) > 0) {
		A25_DI::Redirector()->redirect( 'index2.php?option=com_course&task=viewA&id=' . $id,
				'Cannot cancel this course.  There are Pending or Completed Enrollments');
	}

	if (!$course->course_id) {
		throw new A25_Exception_IllegalAction('No such course exists!');
	} elseif ($course->status_id == 3) {
		throw new A25_Exception_IllegalAction('You cannot cancel a '
				. 'course that is closed!');
	} elseif ($course->status_id == 4) {
		throw new A25_Exception_IllegalAction('You cannot cancel a '
			. 'course that is already cancelled!');
	}

	$lists = array();

	ADMIN_HTML_course::cancelForm( $course, $lists, $option );
}


/**
 * Cancels a course and sends a course message to the enrolled students.
 *
 * @param $id
 * @return void
 */
function cancelCourse($id)
{
	$course = A25_Record_Course::retrieve($id);

	$course->status_id = A25_Record_Course::statusId_Cancelled;
	$course->checkAndStore();
    $subject = $_POST['subject'];
	
	$count = $course->emailStudents($subject, $_POST['message']);

	$course->cancelAllEnrollments();

    $body = "The course you were scheduled to teach has been canceled.\n\n" .
            "Course Date: " . $course->prettyDateTime() . "\n" .
            "Course Location: " . $course->getLocationName();
    
    if ($course->relatedIsDefined('Instructor')) {
        $instructor = $course->Instructor;
    A25_DI::Mailer()->mail($instructor->email, $subject, $body, false);
    }
    if ($course->relatedIsDefined('Instructor2')) {
        $instructor = $course->Instructor2;
        A25_DI::Mailer()->mail($instructor->email, $subject, $body, false);
    }

	$msg = 'Sent course message to ' . $count . ' students.';
	A25_DI::Redirector()->redirect('index2.php?option=com_course&task=viewA&id='
			. $id, $msg);
}


/**
 * Submits a course for payment.
 * @author Christiaan van Woudenberg
 * @version August 27, 2006
 *
 * @param $id
 * @param $option
 * @return void
 */
function payCourse( $id ) {
	global $mosConfig_debug, $mosConfig_debug_email, $my;
	global $mosConfig_fromname, $mosConfig_mailfrom;

	// load the row from the db table
	$course = A25_Record_Course::retrieve( $id );
  
  if (!$course->checkEnrollment())
    return;

	// Close course, mark paid
	$course->status_id = 3;
	$course->is_paid = 1;
	$course->modified = date( 'Y-m-d H:i:s' );
	$course->modified_by = $my->id;

	$course->checkAndStore();

	// Set debug mode for sending e-mail messages
	if ($mosConfig_debug || $mosConfig_debug_email) {
		$debug = true;
	} else {
		$debug = false;
	}

	if ($debug) {
		$recipient = '"' . $mosConfig_fromname . '" <' . $mosConfig_mailfrom . '>'; // debug email.
	} else {
		$recipient = ServerConfig::timesheetRecipientEmailAddress();
	}

	$sender = A25_Record_User::retrieve( $my->id );
	$instructor = A25_Record_User::retrieve( $course->instructor_id );

	$split = array();
	if ($course->instructor_2_id > 0 && $_POST['split']) {
		$split = explode('|',$_POST['split']);
	} else {
		$split = array($instructor->single_fee);
	}

	$str = "";
	$str .= "Course Details\n------------------------------------------------------------------\n";
	$str .= "Course ID:             " . $course->course_id . "\n";
	$str .= "Location Name:         " . $course->Location->location_name . "\n";
	$str .= "Course Date and Time:  " . $course->formattedDate('course_start_date', A25_Functions::PHP_DATE_FORMAT) . "\n\n";

	$str .= "Students\n------------------------------------------------------------------\n";


	$stats = $course->countEnrollmentStatuses();
	foreach ($stats as $statusName => $count) {
		if($count > 0) {
			$str .= str_pad($statusName.':',23) . $count . "\n";
		}
	}


	$str .= "Course Revenue:        $" . number_format($course->getGrossRevenue(),2) . "\n\n";

	$str .= "Primary Instructor\n------------------------------------------------------------------\n";
	$str .= "Instructor Name:       " . $instructor->name . "\n";
	$str .= "Instructor E-mail:     " . $instructor->email . "\n";
	$str .= "Address:               " . $instructor->address_1 . "\n";
	if (strlen($instructor->address_2)) {
	$str .= "                       " . $instructor->address_2 . "\n";
	}
	$str .= "                       " . $instructor->city . "\n";
	$str .= "                       " . $instructor->state . ", " . $instructor->zip . "\n";
	if (strlen($instructor->work_phone)) {
		$str .= "Work Phone:            " . $instructor->work_phone . "\n";
	}
	$str .= "Payment Amount:        $" . number_format($split[0],2);

	if ($course->instructor_2_id > 0) {
		$instructor_2 = A25_Record_User::retrieve( $course->instructor_2_id );

		$str .= "\n\nSecondary Instructor\n------------------------------------------------------------------\n";
		$str .= "Instructor Name:       " . $instructor_2->name . "\n";
		$str .= "Instructor E-mail:     " . $instructor_2->email . "\n";
		$str .= "Address:               " . $instructor_2->address_1 . "\n";
		if (strlen($instructor_2->address_2)) {
		$str .= "                       " . $instructor_2->address_2 . "\n";
		}
		$str .= "                       " . $instructor_2->city . "\n";
		$str .= "                       " . $instructor_2->state . ", " . $instructor_2->zip . "\n";
		if (strlen($instructor_2->work_phone)) {
		$str .= "Work Phone:            " . $instructor_2->work_phone . "\n";
		$str .= "Payment Amount:        $" . number_format($split[1],2);
		}
	}
  
  $str .= "\n\nComments\n------------------------------------------------------------------\n";
  $str .= $course->Comments->comments;

    $subject = PlatformConfig::courseTitle . ": Course Payment Request";
    $body = sprintf("Course Payment Request\n\nFor Instructor(s):\n%s", $str);

  // Send email to administrator, cc instructor(s)
  if (!$debug) {
  	$cc = array();
  	$cc[] = '"' . $instructor->name . '" <' . $instructor->email . '>';
  	if ($instructor_2->email) { $cc[] = '"' . $instructor_2->name . '" <' . $instructor_2->email . '>'; }
		mosMail(A25_DI::PlatformConfig()->sendFromEmail, $sender->name, $recipient, $subject, $body, 0, $cc, 'aliveat25copies@gmail.com', null, $sender->email);
  } else {
		mosMail(A25_DI::PlatformConfig()->sendFromEmail, $sender->name, $recipient, $subject . ' (Debug mode active!)', $body, 0, null, 'aliveat25copies@gmail.com', null, $sender->email);
  }

	$msg = 'Successfully Submitted Course For Payment';
	A25_DI::Redirector()->redirect( 'index2.php?option=com_course&task=viewA&id='. $course->course_id, $msg );
}



/**
 * Check permissions for the given location and task
 *
 * @return bool
 */
function checkPerms($location_id, $task) {
	global $locs, $acl, $my;
	
	return A25_CheckPermissionsMethod::run('course', $location_id,
			$task, $locs, $acl, $my);
}