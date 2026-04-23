<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_debug, $acl, $mainframe, $mosConfig_emailpass, $option;

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_student' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mosConfig_absolute_path . '/autoload.php' );
require_once( $mosConfig_absolute_path . '/administrator/components/com_pay/pay.class.php' );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage any students.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );
$cid = mosGetParam( $_REQUEST, 'cid', array( 0 ) );
$id = mosGetParam( $_REQUEST, 'id', 0 );

switch ($task) {
	case "new":
		A25_FormLoader::run('Student','option=com_student');
		break;

	case 'view':
		viewStudent( intval( $cid[0] ), $option );
		break;

	case 'viewA':
		viewStudent( $id, $option );
		break;

	case 'noteform':
		noteForm( $id, $option );
		break;

	case 'savenote':
		saveNote();
		break;

	case 'msgform':
		msgForm( $id, $option );
		break;

	case 'sendmsg':
		sendMsg();
		break;

	case 'enrolledit':
		A25_Allow::administratorOrHigher();
	    $xref_id = (int) mosGetParam( $_REQUEST, 'xref_id' );
		$enroll = A25_Record_Enroll::retrieve($xref_id);
		$me = new A25_Form_Record_Enroll($enroll,
				'option=com_student&task=viewA&id='
				. $enroll->student_id);
		$me->run($_POST);
	    break;

	case 'enrollview':
	    $xref_id = (int) mosGetParam( $_REQUEST, 'xref_id' );
	    viewEnrollment( $xref_id );
	    break;

	case 'enrollform':
		$location_id = (int) mosGetParam( $_REQUEST, 'location_id', 0 );
		enrollForm( $id, $location_id, $option );
		break;

	case 'enroll':
		enroll();
		break;

	case "edit":
	case 'editA':
	case "studentForm":
		A25_FormLoader::run('Student',
				'option=com_student&task=viewA&id='.$id);
		break;

	case 'cancelEnrollment':
	    $xref_id = (int) mosGetParam( $_REQUEST, 'xref_id' );
		cancelEnrollment( $id, $xref_id );
		break;

	case 'newFee':
		A25_Allow::administratorOrHigher();

		$student = Doctrine_Query::create()
				->select('s.student_id')
				->from('A25_Record_Student s')
				->innerJoin('s.Enrollments x')
				->innerJoin('x.Order o')
				->where('o.order_id = ?', $_GET['order_id'])
				->fetchOne();
		
		$item = new A25_Record_OrderItem();
		$item->order_id = $_GET['order_id'];
		$form = new A25_Form_Record_OrderItem($item,
				"option=com_student&task=viewA&id=$student->student_id");
		$form->run($_POST);
		break;
	
	case 'editItemAmount':
		A25_Allow::administratorOrHigher();

	    $item_id = (int) mosGetParam( $_REQUEST, 'item_id' );
		$item = A25_Record_OrderItem::retrieve($item_id);
		$form = new A25_Form_Record_OrderItemPrice($item,
				'option=com_student&task=viewA&id='
				. $item->studentId());
		$form->run($_POST);
		break;

	case 'waiveOrderItem':
		A25_Allow::administratorOrHigher();
	    $item_id = (int) mosGetParam( $_REQUEST, 'item_id' );
		$item = A25_Record_OrderItem::retrieve($item_id);
		$item->waive();
		$item->save();
		$item->getStudent()->updateOrdersAndEnrollmentsAfterPayment();

		A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
				. $item->studentId(), 'Fee has been waived.');
		break;

	case 'unwaiveOrderItem':
		A25_Allow::administratorOrHigher();
	    $item_id = (int) mosGetParam( $_REQUEST, 'item_id' );
		$item = A25_Record_OrderItem::retrieve($item_id);
		$item->unwaive();
		$item->save();

		A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
				. $item->studentId(), 'Fee has been unwaived.');
		break;
	
	case 'confirmOrderItem':
		A25_Allow::administratorOrHigher();
	    $item_id = (int) mosGetParam( $_REQUEST, 'item_id' );
		$item = A25_Record_OrderItem::retrieve($item_id);
		$item->waive_type = A25_Record_OrderItem::waiveType_StudentConfirmed;
		$item->save();
		$item->getStudent()->updateOrdersAndEnrollmentsAfterPayment();

		A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
				. $item->studentId(), 'Fee has been confirmed waived.');
		break;

	case "remove":
		removeStudent( $cid, $option );
		break;

	case "sendEnrollmentEmail":
		sendEnrollmentEmail( $id );
		break;

	case "sendCompletionEmail":
		sendCompletionEmail( $id );
		break;

	case "sendReminderEmail":
		sendReminderEmail( $id );
		break;

	case "list":
		listStudent($option);
		break;
	
	default:
		listStudent($option, true);
		break;
}

/**
 * Lists all students in alphabetical order, with the option to filter by state.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function listStudent($option, $onlyShowForm = false) {
	global $database, $mosConfig_list_limit, $locs, $my;
	$lists = array();

	$limit = ($_GET['limit'] > 0) ? $_GET['limit'] : $mosConfig_list_limit;
	$limitstart = intval($_GET['limitstart']);
	$filter_status 	= $_GET['filter_status'];
	$filter_court 	= $_GET['filter_court'];
	$filter_first_name = $_GET['filter_first_name'];
	$filter_first_name = $database->getEscaped( trim( strtolower( $filter_first_name ) ) );
	$lists['filter_first_name'] = $filter_first_name;

	$filter_student_id = $_GET['filter_student_id'];
	$filter_student_id = $database->getEscaped( trim( strtolower( $filter_student_id ) ) );
	$lists['filter_student_id'] = $filter_student_id;

	$filter_last_name = $_GET['filter_last_name'];
	$filter_last_name = $database->getEscaped( trim( strtolower( $filter_last_name ) ) );
	$lists['filter_last_name'] = $filter_last_name;

	$filter_email = $_GET['filter_email'];
	$filter_email = $database->getEscaped( trim( strtolower( $filter_email ) ) );
	$lists['filter_email'] = $filter_email;

	$filter_city = $_GET['filter_city'];
	$filter_city = $database->getEscaped( trim( strtolower( $filter_city ) ) );
	$lists['filter_city'] = $filter_city;

	$userRecord = A25_Record_User::retrieve($my->id);
	if (!$onlyShowForm) {
		$where = array();

		$courtIds = $userRecord->courtIds();
		if ($my->isCourtAdministrator()) {
			$where[] = 'x.`court_id` IN (' . implode(',',$courtIds) . ')';
		}

		if ( $filter_student_id ) {
			$where[] = "s.`student_id`=" . (int) $filter_student_id . "";
		}

		if ( $filter_first_name ) {
			$where[] = "LOWER( s.`first_name` ) LIKE '%$filter_first_name%'";
		}

		if ( $filter_last_name ) {
			$where[] = "LOWER( s.`last_name` ) LIKE '%$filter_last_name%'";
		}

		if ( $filter_email ) {
			$where[] = "LOWER( s.`email` ) LIKE '%$filter_email%'";
		}

		if ( $filter_city ) {
			$where[] = "s.`city` LIKE '%$filter_city%'";
		}

		if ( $filter_status ) {
			$where[] = "x.`status_id`='$filter_status'";
		}

		if ( $filter_court ) {
			$where[] = "x.`court_id`='$filter_court'";
		}

		$return = fireAfterFiltersAdminListStudents($lists, $where,
				$database, $option);
		$lists = $return['lists'];
		$where = $return['where'];

		// get the total number of records
		$query = "SELECT COUNT(DISTINCT s.`student_id`)"
		. "\n FROM #__student s"
		. "\n LEFT JOIN #__student_course_xref x USING (`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		;
		$database->setQuery( $query );
		$total = $database->loadResult();

		if ($total < $limitstart) { $limitstart = 0; }

		require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $total, $limitstart, $limit );

		$sql = "SELECT x.*,s.*,t.court_name, u.name AS editor"
		. "\n FROM #__student s"
		. "\n LEFT JOIN #__student_course_xref x ON (s.`student_id` = x.`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__court t ON (x.`court_id` = t.`court_id`)"
		. "\n LEFT JOIN #__users u ON u.id = s.checked_out"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY s.`student_id`"
		. "\n ORDER BY s.last_name, s.first_name, c.created DESC"
		. "\n LIMIT $pageNav->limitstart, $pageNav->limit";
		$database->setQuery( $sql );
		$rows = $database->loadObjectList();
		if (count($rows) == 1) {
			$redirector = new A25_Redirector();
			$redirector->redirect('index2.php?option=com_student&task=viewA&id='
					. $rows[0]->student_id);
		}
		
		echo $database->_errorMsg;
	}

	// build list of student status
	$sstatus = array();
	$sstatus[] = mosHTML::makeOption('','- Select Status -');
	$sql = "SELECT `status_id` AS value, `status_name` AS text FROM #__enroll_status;";
	$database->setQuery($sql);
	$sstatus = array_merge($sstatus,$database->loadObjectList());
	$lists['filter_status'] = mosHTML::selectList( $sstatus, 'filter_status', 'id="filter_status"', 'value', 'text', $filter_status);

	// build list of referring courts
	$court = array();
	$court[] = mosHTML::makeOption('','- Select Referring Court -');
	$courts = $userRecord->courts();
	if ($courts) {
		foreach ($courts as $nextCourt) {
			$court[] = mosHtml::makeOption($nextCourt->court_id,
					"$nextCourt->state - " . SUBSTR($nextCourt->court_name,0,25));
		}
	}
	$lists['filter_court'] = mosHTML::selectList( $court, 'filter_court','id="filter_court"', 'value', 'text', $filter_court);

	$sql = "SELECT * FROM #__enroll_status;";
	$database->setQuery($sql);
	$lists['status_id'] = $database->loadObjectList('status_id');
  
  
  $_SESSION['last_search'] = $_SERVER['REQUEST_URI'];

	HTML_student::listStudent($rows, $pageNav, $option, $lists, $onlyShowForm);
}

function fireAfterFiltersAdminListStudents($lists, $where, $database, $option)
{
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_LicenseNo)
		  {
			  $return = $listener->afterFiltersAdminListStudent($lists, $where,
					  $database, $option);
			  $lists = $return['lists'];
			  $where = $return['where'];
		  }
	  }
	  
	  return array( 'lists' => $lists, 'where' => $where);
}

/**
 * View information for an individual student
 * @author Christiaan van Woudenberg
 * @version August 1, 2006
 *
 * @param integer $student_id
 * @param  string $option
 * @return void
 */
function viewStudent( $student_id, $option='com_student' ) {
	A25_OldCom_Admin_ViewStudent::viewStudent($student_id, $option);
}

/**
 * View information for an individual student
 * @author Garey HOffman - mod SG
 * @version January 17, 2007
 *
 * @param integer $xref_id
 * @return void
 */
function sendEnrollmentEmail($xref_id) {
	$enroll = A25_Record_Enroll::retrieve( $xref_id );
	$student = $enroll->Student;

	if(trim($student->email) == '') {
		$msg = $student->first_name . ' ' . $student->last_name
			 . ' has no email. Print and mail the popup.';
	} else {
		$enroll->sendEnrollmentEmail();
		$msg = 'Enrollment Email sent to Student: '. $student->first_name
			 . ' ' . $student->last_name;
	}

	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
			. $enroll->student_id, $msg );
}
function sendCompletionEmail($xref_id) {
	$enroll = A25_Record_Enroll::retrieve( $xref_id );
	$student = $enroll->Student;

	if(trim($student->email) == '') {
		$msg = $student->first_name . ' ' . $student->last_name
			 . ' has no email. Print and mail the popup.';
	} else {
		$enroll->sendCompletionEmailIfNecessary();
		$msg = 'Completion Email sent to Student: '. $student->first_name
			 . ' ' . $student->last_name;
	}

	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
			. $enroll->student_id, $msg );
}
function sendReminderEmail($xref_id) {
	$enroll = A25_Record_Enroll::retrieve( $xref_id );
    $enroll->sent_class_reminder = 0;
    $enroll->save();
    
    $firstUpcomingReminder = new A25_Remind_Students_UpcomingClass_FirstReminder();
    $firstUpcomingReminder->send();
    
    $secondUpcomingReminder = new A25_Remind_Students_UpcomingClass_SecondReminder();
    $secondUpcomingReminder->send();
    
	$student = $enroll->Student;
    $msg = 'Reminder Email sent to Student: '. $student->first_name
         . ' ' . $student->last_name;

	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='
			. $enroll->student_id, $msg );
}


/**
 * Displays form for attaching a note to a student record.
 * @author Christiaan van Woudenberg
 * @version August 1, 2006
 *
 * @param integer $student_id
 * @param  string $option
 * @return void
 */
function noteForm( $student_id, $option='com_student' ) {
	global $database, $my;

	$row = A25_Record_Student::retrieve($student_id);

	$lists = array();

	HTML_student::noteForm( $row, $lists, $option );
}


/**
 * Saves student note to the database.
 * @author Christiaan van Woudenberg
 * @version August 1, 2006
 *
 * @param  string $option
 * @return void
 */
function saveNote( ) {
	global $my, $mosConfig_offset;

	$row = new A25_Record_Note( A25_DI::DB() );

	if ($_POST['note_id'] > 0)
		$row->load($_POST['note_id']);

	if (!$row->bind( $_POST )) {
		throw new A25_Exception_DataConstraint($row->getError());
	}

	$row->checkAndStore();

	$msg = 'Successfully Saved Student Note';
	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='. $row->student_id, $msg );
}

/**
 * Displays form for enrolling a student in a course.
 * @author Christiaan van Woudenberg
 * @version August 17, 2006
 *
 * @param integer $student_id
 * @param  string $option
 * @return void
 */
function enrollForm( $student_id, $location_id, $option='com_student' ) {
	global $database, $my, $locs;

	$row = A25_Record_Student::retrieve( $student_id );

	$lists = array();

	if ($location_id == 0) {
		$where = array();

		if ( @$locs[0] != 'all' ) {
			$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
		}
		$where[] = "LENGTH(l.location_name)>0";
		$where[] = "l.is_location=1";
    $where[] = "published=1";

		$locations = array();
		$locations[] = mosHTML::makeOption(0,'- Select Location -');
		$sql = "SELECT `location_id` AS value, CONCAT(`location_name`,' (',`state`,')') AS text"
			. "\n FROM #__location l"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
			. "\n ORDER BY `location_name`;"
			;
		$database->setQuery($sql);
		$locations = array_merge($locations,$database->loadObjectList());
		$javascript = ' onchange="this.form.submit();"';
		$lists['location_id'] = mosHTML::selectList( $locations, 'location_id', $javascript, 'value', 'text', null);
	} else {
		$location = A25_Record_Location::retrieve( $location_id );
		$lists['location_id'] = $location->location_name . '<input type="hidden" name="location_id" value="' . $location->location_id . '" />';

		$where = array();
		$where[] = "c.`location_id`='" . (int) $location_id . "'";

		//changed to allow course registrations up to 30 days back
		$where[] = "c.`course_start_date` > DATE_SUB(NOW(), INTERVAL 180 DAY)";

        // changed to not show cancelled courses
        // we DO show CLOSED courses to allow registrations in the past
        $where[] = "c.`status_id` NOT IN (4)";

		$_MYSQL_DATE_FORMAT = '%W %M %d, %Y at %h:%i %p';
		$courses = array();
		$courses[] = mosHTML::makeOption(0,'- Select Course -');
		$sql = "SELECT `course_id` AS value, DATE_FORMAT(c.`course_start_date`,'" . $_MYSQL_DATE_FORMAT . "') AS text"
			. "\n FROM #__course c"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
			. "\n ORDER BY c.`course_start_date`;"
			;

        //echo str_replace('#_','jos',$sql);

		$database->setQuery($sql);
		$courses = array_merge($courses,$database->loadObjectList());

		echo $database->_errorMsg;
		$lists['course_id'] = mosHTML::selectList( $courses, 'course_id', '', 'value', 'text', null);


		$lists['is_late'] = mosHTML::yesnoRadioList('is_late','',0);

		$lists['heard_id'] = $location->hearAboutList($isAdmin = true);
		$reasonListGenerator = new A25_MethodObject_GenerateReasonList($location, $row);
		$lists['reason_id'] = $reasonListGenerator->reasonList($isAdmin = true);
	}

	$lists['court_id'] = A25_SelectListGenerator::generateCourtSelectList('court_id','id="court_id"', null);

	HTML_student::enrollForm( $row, $lists, $option );
}

/**
 * Displays the student's enrollment information
 * @author SG
 * @version December 14, 2006
 *
 * @return void
 */
function viewEnrollment( $xref_id ) {
	$enroll = A25_Record_Enroll::retrieve($xref_id);
	HTML_student::viewEnrollment( $enroll );
}

/**
 * Saves student enrollment to the database.
 * @author Christiaan van Woudenberg
 * @version August 1, 2006
 *
 * @param  string $option
 * @return void
 */
function enroll( ) {
  $course_id = intval($_POST['course_id']);
  if($course_id < 1)
    throw new A25_Exception_InvalidEntry('You must select a course');
	$course = A25_Record_Course::retrieve($course_id);
  
  $student_id = intval($_POST['student_id']);
	$student = A25_Record_Student::retrieve($student_id);
  
	$courseRunner = new A25_CourseRunner($student, $course);
	$courseRunner->enrollFromAdmin();
}

/**
 * Displays form for sending a message to a student.
 * @author Christiaan van Woudenberg
 * @version August 8, 2006
 *
 * @param integer $student_id
 * @param  string $option
 * @return void
 */
function msgForm( $student_id, $option='com_student' ) {
	global $my;

	$row = A25_Record_Student::retrieve( $student_id );

	$lists = array();

	HTML_student::msgForm( $row, $lists, $option );
}


/**
 * Sends a student message.
 * @author Christiaan van Woudenberg
 * @version August 8, 2006
 *
 * @param  string $option
 * @return void
 */
function sendMsg() {
	global $database, $my, $mosConfig_offset;

	$row = new A25_Record_StudentMessage();
	if (!$row->bind( $_POST )) {
		throw new A25_Exception_DataConstraint($row->getError());
	}

	$row->checkWithExceptionOnFailure();

	if (!$row->send()) {
		throw new A25_Exception_DataConstraint($row->getError());
	}


	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id='. $row->student_id, $row->_msg );
}

/**
 * Cancels an existing enrollment.
 *
 * @param int $student_id
 * @param int $xref_id
 * @return void
 */
function cancelEnrollment($student_id, $xref_id) {
	$enroll = A25_Record_Enroll::retrieve( $xref_id );

	$msg = $enroll->cancelEnrollment();
  
  $student = A25_Record_Student::retrieve($student_id);
  $student->updateOrdersAndEnrollmentsAfterPayment();

    A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id=' . $student_id, $msg );
}

/**
 * Remove a student from the database.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param integer $student_id
 * @return void
 */
function removeStudent( $cid ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__student"
		. "\n WHERE student_id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			throw new A25_Exception_DataConstraint($database->getErrorMsg());
		}
	}
	A25_DI::Redirector()->redirect( 'index2.php?option=com_student&action=list' );
}
?>
