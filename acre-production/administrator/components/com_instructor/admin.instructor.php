<?php

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_debug, $my;

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_instructor' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );
require_once( $mosConfig_absolute_path . '/administrator/components/com_instructor/instructor.config.php' );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to perform any instructor tasks.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );
$id = mosGetParam( $_REQUEST, 'id', $my->id );

switch ($task) {
	case "supplyform":
		supplyForm( $id, $option );
		break;

	case "savesupply":
		saveSupply( $option );
		break;

	case "cancelsupply":
		A25_DI::Redirector()->redirect('index2.php','Your supply request was cancelled.');
		break;

	case "timeform":
		timeForm( $id, $option );
		break;

	case "savetime":
		saveTime( $option );
		break;

	case "canceltime":
		A25_DI::Redirector()->redirect('index2.php','Your timesheet submission was cancelled.');
		break;
}

/**
 * Shows form for an instructor to submit a supply request.
 * @author Christiaan van Woudenberg
 * @version August 2, 2006
 *
 * @param  string $option
 * @return void
 */
function supplyForm( $instructor_id, $option ) {
	global $database, $locs;
	global $mosConfig_fromname, $mosConfig_mailfrom;

	$row = A25_Record_User::retrieve( $instructor_id );

	$lists = array();

	$where = array();
	$where[] = "u.block=0";

	// build list of instructors
	if ( !@$locs[0] == 'all') {
		$where[] = "x.`location_id` IN (" . implode(',',$locs) . ")";
	}
	$sql = "SELECT DISTINCT u.`id` AS value, CONCAT(u.`name`,IF(u.block,' (Inactive)','')) AS text"
		. "\n FROM #__users u"
		. "\n LEFT JOIN #__location_user_xref x ON (u.id=x.user_id)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);

	$insts = array();
	$insts[] = mosHTML::makeOption('','- Select Instructor -');
	$insts = array_merge($insts,$database->loadObjectList());
	$javascript = 'id="id" onchange="document.adminForm.submit();"';
	$lists['id'] = mosHTML::selectList( $insts, 'id', $javascript, 'value', 'text', $instructor_id);

	// build list of states
	$lists['state'] = A25_SelectListGenerator::generateStateSelectList('state', 'id="state"',$row->state);

	HTML_instructor::supplyForm( $row, $option, $lists );
}


/**
 * Saves supply request, sends e-mail.
 * @author Christiaan van Woudenberg
 * @version August 2, 2006
 *
 * @param  string $option
 * @return void
 */
function saveSupply( $option ) {
	global $mosConfig_debug, $mosConfig_debug_email, $database, $my;
	global $mosConfig_fromname, $mosConfig_mailfrom;

	if ($mosConfig_debug || $mosConfig_debug_email) {
		$debug = true;
	} else {
		$debug = false;
	}

	$instructor = A25_Record_User::retrieve( $_POST['id'] );

	if ($debug) {
		$recipient = '"' . $mosConfig_fromname . '" <' . $mosConfig_mailfrom . '>'; // debug email.
	} else {
		$recipient = ServerConfig::supplyRequestRecipientEmailAddress() . ', ' . $instructor->email;
	}

	$sender = A25_Record_User::retrieve( $my->id );

	$istr = "";
	$istr .= "Instructor Name:       " . $instructor->name . "\n";
	$istr .= "Instructor E-mail:     " . $instructor->email . "\n";
	$istr .= "Deliver To Address:    " . $_POST['address_1'] . "\n";
	if (strlen($_POST['address_2'])) {
	$istr .= "                       " . $_POST['address_2'] . "\n";
	}
	$istr .= "                       " . $_POST['city'] . "\n";
	$istr .= "                       " . $_POST['state'] . "\n";
	$istr .= "                       " . $_POST['zip'] . "\n";
	if (strlen($_POST['phone'])) {
	$istr .= "Phone:                 " . $_POST['phone'] . "\n";
	}

	$subject = _SUPPLY_REQUEST_SUBJECT;
    $body = sprintf(_SUPPLY_REQUEST_MSG, $_POST['qty_requested'], $_POST['supplies'], $istr);

    // Send email to administrator
	A25_DI::Mailer()->mail($recipient, $subject, $body, 0);

	$msg = 'Successfully Sent Supply Request';
	A25_DI::Redirector()->redirect( 'index2.php', $msg );
}

/**
 * Shows form for an instructor to submit a time sheet.
 * @author Christiaan van Woudenberg
 * @version August 21, 2006
 *
 * @param  string $option
 * @return void
 */
function timeForm( $instructor_id, $option ) {
	global $database, $locs;
	global $mosConfig_fromname, $mosConfig_mailfrom;

	$row = A25_Record_User::retrieve( $instructor_id );

	$lists = array();

	$where = array();
	$where[] = "u.block=0";

	// build list of instructors
	if ( !@$locs[0] == 'all') {
		$where[] = "x.`location_id` IN (" . implode(',',$locs) . ")";
	}
	$sql = "SELECT DISTINCT u.`id` AS value, CONCAT(u.`name`,IF(u.block,' (Inactive)','')) AS text"
		. "\n FROM #__users u"
		. "\n LEFT JOIN #__location_user_xref x ON (u.id=x.user_id)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);

	$insts = array();
	$insts[] = mosHTML::makeOption('','- Select Instructor -');
	$insts = array_merge($insts,$database->loadObjectList());
	$javascript = 'id="id" onchange="document.adminForm.submit();"';
	$lists['id'] = mosHTML::selectList( $insts, 'id', $javascript, 'value', 'text', $instructor_id);

	HTML_instructor::timeForm( $row, $option, $lists );
}


/**
 * Saves time sheet, sends e-mail.
 * @author Christiaan van Woudenberg
 * @version August 21, 2006
 *
 * @param  string $option
 * @return void
 */
function saveTime( $option ) {
	global $mosConfig_debug, $mosConfig_debug_email, $database, $my;
	global $mosConfig_fromname, $mosConfig_mailfrom;

	if ($mosConfig_debug || $mosConfig_debug_email) {
		$debug = true;
	} else {
		$debug = false;
	}

	$instructor = A25_Record_User::retrieve( $_POST['id'] );

	if ($debug) {
		$recipient = '"' . $mosConfig_fromname . '" <' . $mosConfig_mailfrom . '>'; // debug email.
	} else {
		$recipient = ServerConfig::timesheetRecipientEmailAddress() . ', ' . $instructor->email;
	}

	$sender = A25_Record_User::retrieve( $my->id );

	$tstr = "";
	$tstr .= "Date:                  " . $_POST['date'] . "\n";
	$tstr .= "Time:                  " . $_POST['time'] . "\n";
	$tstr .= "Time Spent:            " . $_POST['timespent'] . "\n";
	$tstr .= "Location/Description:  " . $_POST['description'] . "\n";

	$istr = "";
	$istr .= "Instructor Name:       " . $instructor->name . "\n";
	$istr .= "Instructor E-mail:     " . $instructor->email . "\n";
	$istr .= "Address:               " . $instructor->address_1 . "\n";
	if (strlen($instructor->address_2)) {
	$istr .= "                       " . $instructor->address_2 . "\n";
	}
	$istr .= "                       " . $instructor->city . "\n";
	$istr .= "                       " . $instructor->state . "\n";
	$istr .= "                       " . $instructor->zip . "\n";
	if (strlen($instructor->work_phone)) {
	$istr .= "Work Phone:            " . $instructor->work_phone . "\n";
	}

	$subject = _TIMESHEET_SUBJECT;
  $body = sprintf(_TIMESHEET_MSG, $tstr, $istr);

  // Send email to administrator
	A25_DI::Mailer()->mail($recipient, $subject, $body, 0);

	$msg = 'Successfully Sent Timesheet';
	A25_DI::Redirector()->redirect( 'index2.php', $msg );
}
?>
