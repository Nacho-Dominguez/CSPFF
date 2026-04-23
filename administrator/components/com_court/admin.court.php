<?php

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_debug, $acl, $mainframe, $mosConfig_emailpass, $option;

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_court' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage courts.' );
}

require_once( $mainframe->getPath( 'admin_html' ) );

// Get list of administerable court locations for the current user.
$locs = A25_Record_Location::getLocs();

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );
$cid = mosGetParam( $_REQUEST, 'cid', array( 0 ) );
$id = mosGetParam( $_REQUEST, 'id', 0 );

switch ($task) {
	case "new":
		if (checkPerms( 0, $task)) {
			editCourt( 0, $option);
		} else {
			throw new A25_Exception_IllegalAction('You do not have '
					. 'permission to create new courts.');
		}
	break;

	case "edit":
		if (checkPerms( intval( $cid[0] ), $task)) {
			editCourt( intval( $cid[0] ), $option);
		} else {
			throw new A25_Exception_IllegalAction('You do not have '
					. 'permission to edit this court.');
		}
		break;

	case 'editA':
		if (checkPerms( $id, $task)) {
			editCourt( $id, $option);
		} else {
			throw new A25_Exception_IllegalAction('You do not have permission to edit this court.');
		}
		break;
		
	case "othercourt":
	    otherCourt( $option );
	    break;

	case "cancel":
		cancelCourt( $option );
		break;

	case "save":
	case 'apply':
		saveCourt( $task );
		break;

	case "remove":
		removeCourt( $cid, $option );
		break;

	case "publish":
		publishCourt( $cid, 1, $option );
		break;

	case "unpublish":
		publishCourt( $cid, 0, $option );
		break;

	case "list":
	default:
		listCourt( $option );
		break;
}

/**
 * Lists all courts in alphabetical order, with the option to filter by state.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function listCourt( $option ) {
	global $database, $mainframe, $mosConfig_list_limit, $locs, $my;

	$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );
	$filter_state 	= $mainframe->getUserStateFromRequest( "filter_state{$option}", 'filter_state', null );
	$filter_active 	= (int) $mainframe->getUserStateFromRequest( "filter_active{$option}", 'filter_active', 0 );
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$where = array();
	// If not allowed to administer locations, check to see if court admin on any courts.

	if ( @$locs[0] != 'all' ) {
		$sql = "SELECT `court_id` FROM #__court_user_xref WHERE `user_id`='" . (int) $my->id . "'";
		$database->setQuery($sql);
		$cids = $database->loadResultArray();

		$lc = count($locs);
		$cc = count($cids);
		if ($lc && $cc) {
			$where[] = "(c.`parent` IN (" . implode(',',$locs) . ") OR c.`court_id` IN (" . implode(',',$cids) . "))";
		} elseif ($lc && !$cc ) {
			$where[] = "(c.`parent` IN (" . implode(',',$locs) . ")";
		} elseif ($cc) {
			$where[] = "c.`court_id` IN (" . implode(',',$cids) . ")";
		}
	}

	if ( $search ) {
		$where[] = "LOWER( c.`court_name` ) LIKE '%$search%'";
	}

	if ( $filter_state ) {
		$where[] = "c.`state`='$filter_state'";
	}

	if ( $filter_active <> 0 ) {
		$where[] = "c.`published`='" . ($filter_active == -1 ? 0 : 1) . "'";
	}

	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__court c"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	if ($total < $limitstart) { $limitstart = 0; }

	require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$sql = "SELECT c.*, u.name AS editor"
	. "\n FROM #__court c"
	. "\n LEFT JOIN #__users u ON u.id = c.checked_out"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	. "\n ORDER BY c.court_name"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery( $sql );
	$rows = $database->loadObjectList();

	// build list of states
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['filter_state'] = A25_SelectListGenerator::generateStateSelectList('filter_state',$javascript,$filter_state);
	
	// build list of active
	$active = array();
	$active[] = mosHTML::makeOption(0,'- Show All -');
	$active[] = mosHTML::makeOption(-1,'- Show Inactive -');
	$active[] = mosHTML::makeOption(1,'- Show Active -');
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['filter_active'] = mosHTML::selectList( $active, 'filter_active', $javascript, 'value', 'text', $filter_active);

	HTML_court::listCourt( $rows, $pageNav, $search, $option, $lists );
}


/**
 * Edit information for an individual court, or add a new court.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param integer $court_id
 * @param  string $option
 * @return void
 */
function editCourt( $court_id='0', $option='com_court' ) {
	global $my, $locs;

	A25_OldCom_Admin_EditCourt::run($my, $locs, $court_id, $option);
}




/**
 * Saves court information to the database.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function saveCourt( $task ) {
	global $database, $my, $mainframe, $mosConfig_offset;

	$row = new A25_Record_Court();

	if ($_POST['court_id'] > 0)
		$row = A25_Record_Court::retrieve($_POST['court_id']);

	if (!$row->bind( $_POST )) {
		throw new A25_Exception_DataConstraint($row->getError());
	}

	$row->checkAndStore();

	$row->checkin();

	// Update court administrators
	if (@is_array($_POST['currAdmins']) || ($_POST['oldAdmins'] && !isset($_POST['currAdmins']))) {
		if (isset($_POST['currAdmins'])) {
			$new = $_POST['currAdmins'];
		} else {
			$new = array();
		}
		$old = explode(',',$_POST['oldAdmins']);
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);

		if (count($add)) {
			$sqlstr = "INSERT INTO #__court_user_xref (`user_id`,`court_id`) VALUES (%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$item,$row->court_id);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		// What does this do? Nothing methinks.
		if (count($del)) {
			$sqlstr = "DELETE FROM #__court_user_xref WHERE `user_id`=%u AND `court_id`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$item,$row->court_id);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
		
		// Update student enrollment record if this is referenced from
		// another court entry.
		if ( mosGetParam( $_POST, "xref_id" ) ) {
		    $sql = "UPDATE #__student_course_xref"
		    . "\n SET `court_id` = " . $row->court_id . ", `court_other` = NULL"
		    . "\n WHERE `xref_id` = " . mosGetParam( $_POST, "xref_id" );
		    $database->setQuery($sql);
		    $database->query($sql);
		}
	}

	$msg = 'Successfully Saved Court: '. $row->court_name;
	switch ( $task ) {
		case 'apply':
			A25_DI::Redirector()->redirect( 'index2.php?option=com_court&task=editA&hidemainmenu=1&id='. $row->court_id, $msg );
			break;

		case 'save':
		default:
			A25_DI::Redirector()->redirect( 'index2.php?option=com_court&task=list', $msg );
			break;
	}
}


/**
 * Cancels the editing of a court, checks in row.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
function cancelCourt() {
	global $database;

	$row = A25_Record_Court::retrieve($_POST['court_id']);
	if ($row)
		$row->checkin();

	A25_DI::Redirector()->redirect( 'index2.php?option=com_court&action=list' );
}


/**
 * Publishes or unpublishes one or more courts.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
function publishCourt( $cid, $publish=1 ) {
	global $database, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		throw new A25_Exception_DataConstraint('Select an item to $action');
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__court"
	. "\n SET published = " . intval( $publish )
	. "\n WHERE court_id IN ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		throw new A25_Exception_DataConstraint($database->getErrorMsg());
	}
	A25_DI::Redirector()->redirect( 'index2.php?option=com_court&action=list' );
}


/**
 * Remove a court from the database.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param integer $court_id
 * @return void
 */
function removeCourt( $cid ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__court"
		. "\n WHERE court_id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			throw new A25_Exception_DataConstraint(
					$database->getErrorMsg());
		}
	}
	A25_DI::Redirector()->redirect( 'index2.php?option=com_court&action=list' );
}

/**
 * Lists the "other" courts enterred by registrants/students.
 * From here courts can be added or existing courts can be
 * assigned to the registrants by admins
 *
 * @param string $option
 */
function otherCourt( $option ) {
    global $database;
    
    $sql = "SELECT * FROM #__student_course_xref x"
    . "\n LEFT JOIN #__student s ON (x.`student_id` = s.`student_id`)"
    . "\n WHERE `court_other` IS NOT NULL";
    $database->setQuery($sql);
    $rows = $database->loadObjectList();
    //echo "<pre>"; echo str_replace('#_','jos',$sql); echo "</pre>";
    
	HTML_court::otherCourt( $rows, $option );
}

/**
 * Check permissions for the given location and task
 *
 * @return bool
 */
function checkPerms($location_id, $task) {
	global $locs, $acl, $my;
	
	return A25_CheckPermissionsMethod::run('court', $location_id,
			$task, $locs, $acl, $my);
}
?>
