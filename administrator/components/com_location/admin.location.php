<?php
/**
* @version $Id$
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_debug, $acl, $mainframe, $mosConfig_emailpass, $option;

if (!($acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'all' ) || $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'components', 'com_location' ))) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage locations.' );
}

require_once( $mainframe->getPath( 'admin_html' ) );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage any locations.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );
$cid = mosGetParam( $_REQUEST, 'cid', array( 0 ) );
$id = mosGetParam( $_REQUEST, 'id', 0 );

switch ($task) {
	case "A25Config":
        A25_ConfigDisplayer::displayMasterList();
		break;
	case "new":
	case "newparent":
		if (checkPerms(0, $task)) {
			editLocation( 0, $option, $task );
		}
		break;

	case "edit":
		if (checkPerms(intval( $cid[0] ), $task)) {
			editLocation( intval( $cid[0] ), $option, $task );
		} else {
			throw new A25_Exception_IllegalAction('You do not have '
				. 'permission to edit this location.');
		}
		break;

	case 'editA':
		if (checkPerms($id, $task)) {
			editLocation( $id, $option, $task );
		} else {
			throw new A25_Exception_IllegalAction('You do not have '
				. 'permission to edit this location.');
		}
		break;

	case "cancel":
		cancelLocation( $option );
		break;

	case "save":
	case "apply":
		if (checkPerms($_POST['location_id'], $task)) {
			saveLocation( $task );
		} else {
			cancelLocation( $option );
			throw new A25_Exception_IllegalAction('You do not have '
					. 'permission to save this location.');
		}
		break;

	case "addheard":
		$hearAboutType = new A25_Record_HearAboutType();
		$hearAboutType->location_id = $id;

		$returnUrl = 'option=com_location&task=editA&id=' . $id;
		$form = new A25_Form_Record_HearAboutType($hearAboutType, $returnUrl);
		$form->run($_POST);
	    break;

	case "addreason":
		$reasonType = new A25_Record_ReasonType();
		$reasonType->location_id = $id;

		$returnUrl = 'option=com_location&task=editA&id=' . $id;
		$form = new A25_Form_Record_ReasonType($reasonType, $returnUrl);
		$form->run($_POST);
	    break;

	case "editheard":
	    $hear_id = $_GET['hid'];
		$hearAboutType = A25_Record_HearAboutType::retrieve($_GET['hid']);

		$returnUrl = 'option=com_location&task=editA&id=' . $_GET['id'];
		$form = new A25_Form_Record_HearAboutType($hearAboutType, $returnUrl);
		$form->run($_POST);
	    break;

	case "editreason":
	    $reason_id = $_GET['rid'];
		$reasonType = A25_Record_ReasonType::retrieve($_GET['rid']);

		$returnUrl = 'option=com_location&task=editA&id=' . $_GET['id'];
		$form = new A25_Form_Record_ReasonType($reasonType, $returnUrl);
		$form->run($_POST);

	    break;

	case "delheard":
	    $id = $_GET['id'];
	    $hear_id = $_GET['hid'];
	    deleteHeard( $id, $hear_id );
	    break;

	case "delreason":
	    $id = $_GET['id'];
	    $reason_id = $_GET['rid'];
	    deleteReason( $id, $reason_id );
	    break;

	case "remove":
		if (checkPerms($cid, $task)) {
			removeLocation( $cid, $option );
		} else {
			throw new A25_Exception_IllegalAction('You do not have permission to delete one or more of the specified locations.');
		}
		break;

	case "publish":
		if (checkPerms($cid, $task)) {
			publishLocation( $cid, 1, $option );
		} else {
			throw new A25_Exception_IllegalAction('You do not have permission to publish one or more of the specified locations.');
		}
		break;

	case "unpublish":
		if (checkPerms($cid, $task)) {
			publishLocation( $cid, 0, $option );
		} else {
			throw new A25_Exception_IllegalAction('You do not have permission to unpublish one or more of the specified locations.');
		}
		break;

	case "list":
	default:
		listLocation( $option );
		break;
}

/**
 * Lists all locations in alphabetical order, with the option to filter by state.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function listLocation( $option ) {
	global $database, $mainframe, $mosConfig_list_limit, $acl, $my, $locs;

	$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );
	$filter_state 	= $mainframe->getUserStateFromRequest( "filter_state{$option}", 'filter_state', null );
	$filter_parent 	= $mainframe->getUserStateFromRequest( "filter_parent{$option}", 'filter_parent', 'all' );
	$filter_active 	= (int) $mainframe->getUserStateFromRequest( "filter_active{$option}", 'filter_active', 0 );
	$search = $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search = $database->getEscaped( trim( strtolower( $search ) ) );

	$where = array();
	if ( $search ) {
		$where[] = "LOWER( l.`location_name` ) LIKE '%$search%'";
	}

	if ( $filter_state ) {
		$where[] = "l.`state`='$filter_state'";
	}

	/* Only show physical locations */
	if ( $filter_parent == -1 ) {
		$where[] = "l.`is_location`=1";
	}

	/* Only show location parents */
	if ( $filter_parent == 1 ) {
		$where[] = "l.`is_location`=0";
	}

	if ( @$locs[0] != 'all' ) {
		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
	}

	if ( $filter_active <> 0 ) {
		$where[] = "l.`published`='" . ($filter_active == -1 ? 0 : 1) . "'";
	}

	// get the total number of records
	$query = "SELECT COUNT(*)"
	. "\n FROM #__location l"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	;
	$database->setQuery( $query );
	$total = $database->loadResult();
	if ($total < $limitstart) { $limitstart = 0; }

	require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	$sql = "SELECT l.*, u.name AS editor"
	. "\n FROM #__location l"
	. "\n LEFT JOIN #__users u ON u.id = l.checked_out"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	. "\n ORDER BY l.location_name"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit";
	$database->setQuery( $sql );
	$rows = $database->loadObjectList();

	// build list of location types
	$parents = array();
	$parents[] = mosHTML::makeOption('all','- Show All Locations -');
	$parents[] = mosHTML::makeOption(-1,'Physical Locations Only');
	$parents[] = mosHTML::makeOption(1,'Location Parents Only');

	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['filter_parent'] = mosHTML::selectList( $parents, 'filter_parent', $javascript, 'value', 'text', $filter_parent);

	// build list of states
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['filter_state'] = A25_SelectListGenerator::generateStateSelectList('filter_state', $javascript, $filter_state);

	// build list of active
	$active = array();
	$active[] = mosHTML::makeOption(0,'- Show All -');
	$active[] = mosHTML::makeOption(-1,'- Show Inactive -');
	$active[] = mosHTML::makeOption(1,'- Show Active -');
	$javascript = 'onchange="document.adminForm.submit();"';
	$lists['filter_active'] = mosHTML::selectList( $active, 'filter_active', $javascript, 'value', 'text', $filter_active);

	HTML_location::listLocation( $rows, $pageNav, $search, $option, $lists );
}


/**
 * Edit information for an individual location, or add a new location.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param integer $location_id
 * @param  string $option
 * @param  string $task
 * @return void
 */
function editLocation( $location_id='0', $option='com_location', $task ) {
	global $database, $my, $locs, $acl;
	global $mosConfig_absolute_path, $mosConfig_live_site;


	if ($location_id) {
		// do stuff for existing records
		$location = A25_Record_Location::retrieve( $location_id );
		$location->checkout($my->id);
	} else {
		// do stuff for new records
		$location = new A25_Record_Location();
		$location->published = PlatformConfig::defaultPublished;
		$location->parent = PlatformConfig::defaultParent;
	}

	$lists = array();

	/**
	 * Restrict location administrators to a subset of location parents, and
	 * deny the ability to edit the location parent of a location for which
	 * they do not have administrative abilities to its parent.
	 */
	$canChangeParent = true;

	/* Cannot change parent for root location */
	if ($location->location_id == 2) {
		$canChangeParent = false;
	}

	/* Allow all locations */
	if ($acl->acl_check( 'action', 'all', 'users', $my->usertype, 'location', 'all' )) {
		$sql = "SELECT `location_id` AS `id`, `parent`, `location_name` AS `name`"
			. "\n FROM #__location"
			. "\n WHERE NOT(`is_location`)"
			. "\n ORDER BY `name`"
			;
	}

	/* Deny assignment of parent if user cannot edit parent. Disable for new records. */
	elseif ($location_id && !array_key_exists($location->parent, $locs)) {
		$canChangeParent = false;
	}

	/* Filter by locations */
	else {
		$sql = "SELECT `location_id` AS `id`, `parent`, `location_name` AS `name`"
			. "\n FROM #__location"
			. "\n WHERE NOT(`is_location`)"
			. "\n ORDER BY `name`"
			;
	}

	if ($canChangeParent) {
		$database->setQuery($sql);
		$src_list = $database->loadObjectList();

		$sel = array();
		$sel[] = mosHTML::makeOption($location->parent);

		$lists['parent'] = mosHTML::selectList($src_list, 'parent', ' class="inputbox" size="1"', 'id', 'name', $sel );
		if ($location->location_id) {
			$lists['parent'] .= mosToolTip('Changing the location parent will change which location administrators have permissions to manage this location.', 'Warning!',null,'warning.png');
		}
	} else {
		$lists['parent'] = '<i>You do not have permission to change the parent assignment of this location.</i>';
	}

	$lists['is_highschool'] = mosHTML::yesnoradioList( 'is_highschool', '', $location->is_highschool );
    
	$lists['virtual'] = mosHTML::yesnoradioList( 'virtual', '', $location->virtual );

	$lists['published'] = mosHTML::yesnoradioList( 'published', '', $location->published );

	// Build list of available location administrators
	$sql = "SELECT u.`id` AS value, CONCAT(u.`name`,IF(u.`block`,\" (Inactive)\",\"\"))  AS text"
		. "\n FROM #__users u"
		. "\n LEFT JOIN #__location_user_xref x ON (u.`id` = x.`user_id` AND x.`location_id`=" . (int) $location->location_id . " AND x.`gid`=27)"
		. "\n WHERE x.`xref_id` IS NULL"
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);
	$availAdmins = $database->loadObjectList();
	echo $database->_errorMsg;

	$lists['availAdmins'] = mosHTML::selectList( $availAdmins, 'availAdmins[]', 'id = "availAdmins" class="inputbox" size="8" multiple="multiple"', 'value', 'text');

	// Build list of current location administrators
	$sql = "SELECT u.`id` AS value, CONCAT(u.`name`,IF(u.`block`,\" (Inactive)\",\"\"))  AS text"
		. "\n FROM #__location_user_xref x"
		. "\n LEFT JOIN #__users u ON (x.`user_id` = u.`id`)"
		. "\n WHERE x.`location_id`=" . (int) $location->location_id
		. "\n AND x.gid=27"
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);
	$currAdmins = $database->loadObjectList();
	echo $database->_errorMsg;

	$lists['currAdmins'] = mosHTML::selectList( $currAdmins, 'currAdmins[]', 'id = "currAdmins" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
	$currKeys = $database->loadResultList('value');
	$lists['currAdmins'] .= '<input type="hidden" name="oldAdmins" value="' . implode(',',array_keys($currKeys)) . '" />';

	// Build list of available instructors
	$sql = "SELECT u.`id` AS value, CONCAT(u.`name`,IF(u.`block`,\" (Inactive)\",\"\"))  AS text"
		. "\n FROM #__users u"
		. "\n LEFT JOIN #__location_user_xref x ON (u.`id` = x.`user_id` AND x.`location_id`=" . (int) $location->location_id . " AND x.`gid`=27)"
		. "\n WHERE x.`xref_id` IS NULL"
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);
	$availInsts = $database->loadObjectList();
	echo $database->_errorMsg;

	$lists['availInsts'] = mosHTML::selectList( $availInsts, 'availInsts[]', 'id = "availInsts" class="inputbox" size="8" multiple="multiple"', 'value', 'text');

	// Build list of current instructors
	$sql = "SELECT u.`id` AS value, CONCAT(u.`name`,IF(u.`block`,\" (Inactive)\",\"\"))  AS text"
		. "\n FROM #__location_user_xref x"
		. "\n LEFT JOIN #__users u ON (x.`user_id` = u.`id`)"
		. "\n WHERE x.`location_id`=" . (int) $location->location_id
		. "\n AND x.gid=26"
		. "\n ORDER BY u.`name`"
		;
	$database->setQuery($sql);
	$currInsts = $database->loadObjectList();
	echo $database->_errorMsg;

	$lists['currInsts'] = mosHTML::selectList( $currInsts, 'currInsts[]', 'id = "currInsts" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
	$currKeys = $database->loadResultList('value');
	$lists['currInsts'] .= '<input type="hidden" name="oldInsts" value="' . implode(',',array_keys($currKeys)) . '" />';

	// list of location specific 'heard about us' items
	$sql = "SELECT * FROM #__hear_about_type"
	   . "\n WHERE `location_id` = " . $location->location_id;
	$database->setQuery($sql);
	$lists['heard'] = $database->loadObjectList();

	// list of location specific 'reason' items
	$sql = "SELECT * FROM #__reason_type"
	   . "\n WHERE `location_id` = " . $location->location_id;
	$database->setQuery($sql);
	$lists['reason'] = $database->loadObjectList();
  
	if ($location->is_location || $task == 'new') {
		// build list of states
		$lists['state'] = A25_SelectListGenerator::generateStateSelectList('state', 'class="inputbox" size="1"',$location->state);

		HTML_location::editLocation( $location, $lists, $option );
	} else {
		HTML_location::editLocationParent( $location, $lists, $option );
	}
}

/**
 * Saves location information to the database.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param  string $option
 * @return void
 */
function saveLocation( $task ) {
	global $database, $my, $mainframe, $mosConfig_offset, $mosConfig_absolute_path;

	if ($_POST['is_location']) {
		if ($_POST['location_id'] > 0)
			$row = A25_Record_Location::retrieve( $_POST['location_id']);
		else
			$row = new A25_Record_Location();
	} else {
		if ($_POST['location_id'] > 0)
			$row = A25_Record_LocationParent::retrieve( $_POST['location_id']);
		else
			$row = new A25_Record_LocationParent();
	}

	if (!$row->bind( $_POST )) {
		throw new A25_Exception_DataConstraint($row->getError());
	}
	
	// code cleaner for xhtml transitional compliance
	$row->description = str_replace( '<br>', '<br />', $row->description );

 	// remove <br /> take being automatically added to empty fulltext
 	$length	= strlen( $row->description ) < 9;
 	$search = strstr( $row->description, '<br />');
 	if ( $length && $search ) {
 		$row->description = NULL;
 	}

	$row->checkAndStore();

	$row->checkin();

	// Update location admin locations
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
			$sqlstr = "INSERT INTO #__location_user_xref (`user_id`,`location_id`,`gid`) VALUES (%u,%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$item,$row->location_id,27);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		if (count($del)) {
			$sqlstr = "DELETE FROM #__location_user_xref WHERE `user_id`=%u AND `location_id`=%u AND `gid`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$item,$row->location_id,27);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
	}

	// Update location instructors
	if (@is_array($_POST['currInsts']) || (@$_POST['oldInsts'] && !isset($_POST['currInsts']))) {
		if (isset($_POST['currInsts'])) {
			$new = $_POST['currInsts'];
		} else {
			$new = array();
		}
		$old = explode(',',$_POST['oldInsts']);
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);

		if (count($add)) {
			$sqlstr = "INSERT INTO #__location_user_xref (`user_id`,`location_id`,`gid`) VALUES (%u,%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$item,$row->location_id,26);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		if (count($del)) {
			$sqlstr = "DELETE FROM #__location_user_xref WHERE `user_id`=%u AND `location_id`=%u AND `gid`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$item,$row->location_id,26);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
	}

	$msg = 'Successfully Saved Location: '. $row->location_name;
	switch ( $task ) {
		case 'apply':
			A25_DI::Redirector()->redirect( 'index2.php?option=com_location&task=editA&hidemainmenu=1&id='. $row->location_id, $msg );
			break;

		case 'save':
		default:
			A25_DI::Redirector()->redirect( 'index2.php?option=com_location&task=list', $msg );
			break;
	}
}

/**
 * Cancels the editing of a location, checks in row.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
function cancelLocation() {
	global $database;

	$row = A25_Record_Location::retrieve( $_POST['location_id']);
	if ($row) {
		$row->checkin();
	}

	A25_DI::Redirector()->redirect( 'index2.php?option=com_location&action=list' );
}

function addCreditType( $id, $option ) {
    global $database;

    $sql = "SELECT * FROM #__location"
        . "\n WHERE `location_id` = " . $id;
    $database->setQuery( $sql );
    $database->loadObject( $location );

    HTML_location::addCreditType( $location, $option );
}

function saveCreditType( $id ) {
    global $my;

    $row = new A25_Record_CreditType();

	if ($_POST['credit_type_id'] > 0)
		$row = A25_Record_CreditType::retrieve($_POST['credit_type_id']);

    if(!$row->bind( $_POST )) {
		throw new A25_Exception_DataConstraint($row->getError());
    }

	$row->checkAndStore();

	A25_DI::Redirector()->redirect( 'index2.php?option=com_location&task=editA&id=' . $id );
}

function deleteHeard( $id, $hear_id ) {
    global $database;

    $sql = "DELETE FROM #__hear_about_type WHERE hear_about_id = " . $hear_id;
    $database->setQuery( $sql );
    $database->query( $sql );

    A25_DI::Redirector()->redirect( 'index2.php?option=com_location&task=editA&id=' . $id );
}

function deleteReason( $id, $reason_id ) {
    global $database;

    $sql = "DELETE FROM #__reason_type WHERE reason_id = " . $reason_id;
    $database->setQuery( $sql );
    $database->query( $sql );

    A25_DI::Redirector()->redirect( 'index2.php?option=com_location&task=editA&id=' . $id );
}

/**
 * Publishes or unpublishes one or more locations.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @return void
 */
function publishLocation( $cid, $publish=1 ) {
	global $database, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		$action = $publish ? 'publish' : 'unpublish';
		throw new A25_Exception_DataConstraint("Select an item to $action");
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__location"
	. "\n SET published = " . intval( $publish )
	. "\n WHERE location_id IN ( $cids )"
	. "\n AND ( checked_out = 0 OR ( checked_out = $my->id ) )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		throw new A25_Exception_DataConstraint($database->getErrorMsg());
	}
	
	A25_DI::Redirector()->redirect( 'index2.php?option=com_location&action=list' );
}


/**
 * Remove a location from the database.
 * @author Christiaan van Woudenberg
 * @version June 20, 2006
 *
 * @param integer $location_id
 * @return void
 */
function removeLocation( $cid ) {
	global $database;
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__location"
		. "\n WHERE location_id IN ( $cids )"
		;
		$database->setQuery( $query );
		if (!$database->query()) {
			throw new A25_Exception_DataConstraint($database->getErrorMsg());
		}
	}
	A25_DI::Redirector()->redirect( 'index2.php?option=com_location&action=list' );
}


/**
 * Check permissions for the given location and task
 *
 * @return bool
 */
function checkPerms($location_id, $task) {
	global $locs, $acl, $my;

	return A25_CheckPermissionsMethod::run('location', $location_id,
			$task, $locs, $acl, $my);
}
?>
