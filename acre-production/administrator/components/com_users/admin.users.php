<?php

if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' )) {
	A25_DI::Redirector()->redirect( 'index2.php', _NOT_AUTH );
}

require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to manage any users.' );
}

$cid 	= mosGetParam( $_REQUEST, 'cid', array( 0 ) );
if (!is_array( $cid )) {
	$cid = array ( 0 );
}

switch ($task) {
	case 'new':
		editUser( 0, $option);
		break;

	case 'edit':
		editUser( intval( $cid[0] ), $option );
		break;

	case 'editA':
		editUser( $id, $option );
		break;

	case 'save':
	case 'apply':
		// check to see if functionality restricted for use as demo site
		if ( $_VERSION->RESTRICT == 1 ) {
			A25_DI::Redirector()->redirect( 'index2.php?mosmsg=Functionality Restricted' );
		} else {
			saveUser( $task );
		}
		break;

	case 'remove':
		removeUsers( $cid, $option );
		break;

	case 'block':
		// check to see if functionality restricted for use as demo site
		if ( $_VERSION->RESTRICT == 1 ) {
			A25_DI::Redirector()->redirect( 'index2.php?mosmsg=Functionality Restricted' );
		} else {
			changeUserBlock( $cid, 1, $option );
		}
		break;

	case 'unblock':
		changeUserBlock( $cid, 0, $option );
		break;

	case 'logout':
		logoutUser( $cid, $option, $task );
		break;

	case 'flogout':
		logoutUser( $id, $option, $task );
		break;

	case 'cancel':
		cancelUser( $option );
		break;

	case 'contact':
		$contact_id = mosGetParam( $_POST, 'contact_id', '' );
		A25_DI::Redirector()->redirect( 'index2.php?option=com_contact&task=editA&id='. $contact_id );
		break;

	default:
		showUsers( $option );
		break;
}

function showUsers( $option ) {
	global $database, $mainframe, $my, $acl, $locs, $mosConfig_list_limit;

	$filter_type	= $mainframe->getUserStateFromRequest( "filter_type{$option}", 'filter_type', 0 );
	$filter_logged	= intval( $mainframe->getUserStateFromRequest( "filter_logged{$option}", 'filter_logged', 0 ) );
	$filter_active 	= (int) $mainframe->getUserStateFromRequest( "filter_active{$option}", 'filter_active', 0 );
	$limit 			= intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
	$limitstart 	= intval( $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 ) );
	$search 		= $mainframe->getUserStateFromRequest( "search{$option}", 'search', '' );
	$search 		= $database->getEscaped( trim( strtolower( $search ) ) );
	$where 			= array();

	if ( @$locs[0] != 'all' ) {
		$where[] = "lx.location_id IN (" . implode(',',$locs) . ")";
	}

	if (isset( $search ) && $search!= "") {
		$where[] = "(a.email LIKE '%$search%' OR a.name LIKE '%$search%')";
	}
	if ( $filter_type ) {
		if ( $filter_type == 'Public Frontend' ) {
			$where[] = "a.usertype = 'Registered' OR a.usertype = 'Author' OR a.usertype = 'Editor'OR a.usertype = 'Publisher'";
		} else if ( $filter_type == 'Public Backend' ) {
			$where[] = "a.usertype = 'Manager' OR a.usertype = 'Administrator' OR a.usertype = 'Super Administrator'";
		} else {
			$where[] = "a.usertype = LOWER( '$filter_type' )";
		}
	}
	if ( $filter_logged == 1 ) {
		$where[] = "s.userid = a.id";
	} else if ($filter_logged == 2) {
		$where[] = "s.userid IS NULL";
	}

	if ( $filter_active <> 0 ) {
		$where[] = "a.`block`='" . ($filter_active == -1 ? 0 : 1) . "'";
	}

	// exclude any child group id's for this user
	//$acl->_debug = true;
	$pgids = $acl->get_group_children( $my->gid, 'ARO', 'RECURSE' );

	if (is_array( $pgids ) && count( $pgids ) > 0) {
		$where[] = "(a.gid NOT IN (" . implode( ',', $pgids ) . "))";
	}
  
  fireAddFilter($mainframe, $where, $lists);

	$query = "SELECT a.id"
	. "\n FROM #__users AS a"
	. "\n LEFT JOIN #__location_user_xref AS lx ON a.id = lx.user_id";

	if ($filter_logged == 1 || $filter_logged == 2) {
		$query .= "\n INNER JOIN #__session AS s ON s.userid = a.id";
	}

	$query .= ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : '' );
	$query .= "\n GROUP BY a.id";
	
	$database->setQuery( $query );
	$ids = $database->loadResultArray();
	$total = count($ids);
	if ($total < $limitstart) { $limitstart = 0; }
	
	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit  );

	$query = "SELECT a.*, g.name AS groupname"
	. "\n FROM #__users AS a"
	. "\n INNER JOIN #__core_acl_aro AS aro ON aro.value = a.id"	// map user to aro
	. "\n INNER JOIN #__core_acl_groups_aro_map AS gm ON gm.aro_id = aro.aro_id"	// map aro to group
	. "\n INNER JOIN #__core_acl_aro_groups AS g ON g.group_id = gm.group_id"
	. "\n LEFT JOIN #__location_user_xref AS lx ON a.id = lx.user_id";

	if ($filter_logged == 1 || $filter_logged == 2) {
		$query .= "\n INNER JOIN #__session AS s ON s.userid = a.id";
	}

	$query .= (count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "")
	. "\n GROUP BY a.id"
	. "\n ORDER BY substring_index(TRIM(a.name), ' ', -1)"
	. "\n LIMIT $pageNav->limitstart, $pageNav->limit"
	;
	$database->setQuery( $query );
	$rows = $database->loadObjectList();

	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}

	$template = 'SELECT COUNT(s.userid) FROM #__session AS s WHERE s.userid = %d';
	$n = count( $rows );
	for ($i = 0; $i < $n; $i++) {
		$row = &$rows[$i];
		$query = sprintf( $template, intval( $row->id ) );
		$database->setQuery( $query );
		$row->loggedin = $database->loadResult();
	}

	// get list of Groups for dropdown filter
	$query = "SELECT name AS value, name AS text"
	. "\n FROM #__core_acl_aro_groups"
	. "\n WHERE name != 'ROOT'"
	. "\n AND name != 'USERS'"
	;
	$types[] = mosHTML::makeOption( '0', '- Select Group -' );
	$database->setQuery( $query );
	$types = array_merge( $types, $database->loadObjectList() );
	$lists['type'] = mosHTML::selectList( $types, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', "$filter_type" );

	// get list of Log Status for dropdown filter
	$logged[] = mosHTML::makeOption( 0, '- Select Log Status - ');
	$logged[] = mosHTML::makeOption( 1, 'Logged In');
	$lists['logged'] = mosHTML::selectList( $logged, 'filter_logged', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', "$filter_logged" );

	// build list of active
	$active = array();
	$active[] = mosHTML::makeOption(0,'- Show All -');
	$active[] = mosHTML::makeOption(1,'- Show Inactive -');
	$active[] = mosHTML::makeOption(-1,'- Show Active -');
	$lists['filter_active'] = mosHTML::selectList( $active, 'filter_active', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_active);
  
	HTML_users::showUsers( $rows, $pageNav, $search, $option, $lists );
}

/**
 * Edit the user
 * @param int The user ID
 * @param string The URL option
 */
function editUser( $uid='0', $option='users' ) {
	global $database, $my, $acl, $mainframe, $locs;

	if ( $uid ) {
		$row = A25_Record_User::retrieve( $uid );
		$query = "SELECT *"
		. "\n FROM #__contact_details"
		. "\n WHERE user_id = $row->id"
		;
		$database->setQuery( $query );
		$contact = $database->loadObjectList();
	} else {
		$row = new A25_Record_User();
		$contact 	= NULL;
		$row->block = 0;
	}

	// check to ensure only super admins can edit super admin info
	if ( ( $my->gid < 25 ) && ( $row->gid == 25 ) ) {
		A25_DI::Redirector()->redirect( 'index2.php?option=com_users', _NOT_AUTH );
	}

	$my_group = strtolower( $acl->get_group_name( $row->gid, 'ARO' ) );
	if ( $my_group == 'super administrator' && $my->gid != 25 ) {
		$lists['gid'] = '<input type="hidden" name="gid" value="'. $my->gid .'" /><strong>Super Administrator</strong>';
	} else if ( $my->gid == 24 && $row->gid == 24 ) {
		$lists['gid'] = '<input type="hidden" name="gid" value="'. $my->gid .'" /><strong>Administrator</strong>';
	} else {
		// ensure user can't add group higher than themselves
		$my_groups = $acl->get_object_groups( 'users', $my->id, 'ARO' );
		if (is_array( $my_groups ) && count( $my_groups ) > 0) {
			$ex_groups = $acl->get_group_children( $my_groups[0], 'ARO', 'RECURSE' );
		} else {
			$ex_groups = array();
		}

		$gtree = $acl->get_group_children_tree( null, 'USERS', false );

		// remove users 'above' me
		$i = 0;
		while ($i < count( $gtree )) {
			if (in_array( $gtree[$i]->value, $ex_groups )) {
				array_splice( $gtree, $i, 1 );
			} else {
				$i++;
			}
		}

		$lists['gid'] 		= mosHTML::selectList( $gtree, 'gid', 'size="10"', 'value', 'text', $row->gid );
	}

	// build the html select list
	$lists['block'] 		= mosHTML::yesnoRadioList( 'block', 'class="inputbox" size="1"', $row->block );
	// build the html select list
	$lists['sendEmail'] 	= mosHTML::yesnoRadioList( 'sendEmail', 'class="inputbox" size="1"', $row->sendEmail );

	// build list of states
	$lists['state'] = A25_SelectListGenerator::generateStateSelectList('state', 'class="inputbox" size="1"',$row->state);

	// Location Administrators: build list of available locations
	$availLocAdminLocs = array();
	$where = array();
	//$where[] = "x.`location_id` IS NULL";

	if ( @$locs[0] != 'all' ) {
		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT l.`location_id` AS id, l.`parent`, l.`location_name` AS name"
		 . "\n FROM #__location l"
		 . ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY `parent`,`location_name`;";
	$database->setQuery($sql);
	$availLocAdminLocs = $database->loadObjectList();
	//echo str_replace('#_','jos',$sql);
	$lists['availLocadminLocs'] = mosHTML::treeSelectList($availLocAdminLocs, 0, array(), 'availLocadminLocs', ' id="availLocadminLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text', array() );

	// Location Administrators: build list of current locations
	$currLocadminLocs = array();
	$where = array();
	$where[] = "x.`user_id`=" . (int) $row->id;
	$where[] = "x.`gid`=27";

	if ( @$locs[0] != 'all' ) {
		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT l.`location_id` AS value, l.`location_name` AS text"
		 . "\n FROM #__location l"
		 . "\n LEFT JOIN #__location_user_xref x USING (`location_id`)"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY `location_name`;";
	$database->setQuery($sql);
	$currLocadminLocs = $database->loadObjectList();
	echo $database->_errorMsg;
	$lists['currLocadminLocs'] = mosHTML::selectList( $currLocadminLocs, 'currLocadminLocs[]', 'id = "currLocadminLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
	$currKeys = $database->loadResultList('value');
	$lists['currLocadminLocs'] .= '<input type="hidden" name="oldLocadminLocs" value="' . implode(',',array_keys($currKeys)) . '" />';

	// Instructors: build list of available locations
	$availInstLocs = array();
	$where = array();
	$where[] = "x.`location_id` IS NULL";
	$where[] = "l.`is_location`";

	if ( @$locs[0] != 'all' ) {
		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT l.`location_id` AS value, l.`location_name` AS text"
		 . "\n FROM #__location l"
		 . "\n LEFT JOIN #__location_user_xref x ON (l.`location_id` = x.`location_id` AND x.`user_id`=" . (int) $row->id . " AND x.`gid`=26)"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY `location_name`;";
	$database->setQuery($sql);
	$availInstLocs = $database->loadObjectList();
	echo $database->_errorMsg;
	$lists['availInstLocs'] = mosHTML::selectList( $availInstLocs, 'availInstLocs', 'id = "availInstLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text');

	// Instructors: build list of current locations
	$currInstLocs = array();
	$where = array();
	$where[] = "x.`user_id`=" . (int) $row->id;
	$where[] = "x.`gid`=26";
	$where[] = "l.`is_location`";

	if ( @$locs[0] != 'all' ) {
		$where[] = "l.location_id IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT l.`location_id` AS value, l.`location_name` AS text"
		 . "\n FROM #__location l"
		 . "\n LEFT JOIN #__location_user_xref x USING (`location_id`)"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY `location_name`;";
	$database->setQuery($sql);
	$currInstLocs = $database->loadObjectList();
	$lists['currInstLocs'] = mosHTML::selectList( $currInstLocs, 'currInstLocs[]', 'id = "currInstLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
	$currKeys = $database->loadResultList('value');
	$lists['currInstLocs'] .= '<input type="hidden" name="oldInstLocs" value="' . implode(',',array_keys($currKeys)) . '" />';

	// court administrators: build list of available courts
	$availCourtadminLocs = array();
	$where = array();
	$where[] = "x.`court_id` IS NULL";

	if ( @$locs[0] != 'all' ) {
		$where[] = "c.parent IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT c.`court_id` AS value, c.`court_name` AS text"
		 . "\n FROM #__court c"
		 . "\n LEFT JOIN #__court_user_xref x ON (c.`court_id` = x.`court_id` AND x.`user_id`=" . (int) $row->id . ")"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY c.`court_name`;";
	$database->setQuery($sql);
	$availCourtadminLocs = $database->loadObjectList();
	$lists['availCourtadminLocs'] = mosHTML::selectList( $availCourtadminLocs, 'availCourtadminLocs', 'id = "availCourtadminLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text');

	// court administrators: build list of current courts
	$currCourtadminLocs = array();
	$where = array();
	$where[] = "x.`user_id`=" . (int) $row->id;

	if ( @$locs[0] != 'all' ) {
		$where[] = "c.parent IN (" . implode(',',$locs) . ")";
	}

	$sql = "SELECT c.`court_id` AS value, c.`court_name` AS text"
		 . "\n FROM #__court c"
		 . "\n LEFT JOIN #__court_user_xref x USING (`court_id`)"
			. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		 . "\n ORDER BY c.`court_name`;";
	$database->setQuery($sql);
	$currCourtadminLocs = $database->loadObjectList();
	echo $database->_errorMsg;
	//echo str_replace('#_','jos',$sql);
	$lists['currCourtadminLocs'] = mosHTML::selectList( $currCourtadminLocs, 'currCourtadminLocs[]', 'id = "currCourtadminLocs" class="inputbox" size="8" multiple="multiple"', 'value', 'text');
	$currKeys = $database->loadResultList('value');
	$lists['currCourtadminLocs'] .= '<input type="hidden" name="oldCourtadminLocs" value="' . implode(',',array_keys($currKeys)) . '" />';

	$file 	= $mainframe->getPath( 'com_xml', 'com_users' );
	$params =& new mosUserParameters( $row->params, $file, 'component' );

	HTML_users::edituser( $row, $contact, $lists, $option, $uid, $params );
}

function saveUser( $task ) {
	global $database, $my;
	global $mosConfig_live_site, $mosConfig_mailfrom, $mosConfig_fromname, $mosConfig_sitename;


	if ($_POST['id'] > 0) {
		$row = A25_Record_User::retrieve($_POST['id']);
		$originalGid = $row->gid;
		$originalPassword = $row->password;
		$isNew = false;
	} else {
		$row = new A25_Record_User();
		$isNew = true;
	}

	if (!$row->bind( $_POST, 'availLocadminLocs|currLocadminLocs|availInstLocs|currInstLocs|availCourtadminLocs|currCourtadminLocs' )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}

	$pwd 	= '';

	// MD5 hash convert passwords
	if ($isNew) {
		// new user stuff
		if ($row->password == '') {
			$pwd = mosMakePassword();
			$row->password = md5( $pwd );
		} else {
			$pwd = $row->password;
			$row->password = md5( $row->password );
		}
		$row->registerDate = date( 'Y-m-d H:i:s' );
	} else {

		// existing user stuff
		if ($row->password == '') {
			$row->password = $originalPassword;
		} else {
			$row->password = md5( $row->password );
		}

		// if group has been changed and where original group was a Super Admin
		if ( $row->gid != $originalGid && $originalGid == 25 ) {
			// count number of active super admins
			$query = "SELECT COUNT( id )"
			. "\n FROM #__users"
			. "\n WHERE gid = 25"
			. "\n AND block = 0"
			;
			$database->setQuery( $query );
			$count = $database->loadResult();

			if ( $count <= 1 ) {
				// disallow change if only one Super Admin exists
				echo "<script> alert('You cannot change this users Group as it is the only active Super Administrator for your site'); window.history.go(-1); </script>\n";
				exit();
			}
		}
	}

	// if user is made a Super Admin group and user is NOT a Super Admin
	if ( $row->gid == 25 && $my->gid != 25 ) {
		// disallow creation of Super Admin by non Super Admin users
		echo "<script> alert('You cannot create a user with this user Group level, only Super Administrators have this ability'); window.history.go(-1); </script>\n";
		exit();
	}

	// save usertype to usetype column
	$query = "SELECT name"
	. "\n FROM #__core_acl_aro_groups"
	. "\n WHERE group_id = $row->gid"
	;
	$database->setQuery( $query );
	$usertype = $database->loadResult();
	$row->usertype = $usertype;

	// save params
	$params = mosGetParam( $_POST, 'params', '' );
	if (is_array( $params )) {
		$txt = array();
		foreach ( $params as $k=>$v) {
			$txt[] = "$k=$v";
		}
		$row->params = implode( "\n", $txt );
	}

	$row->checkAndStore();
	$row->checkin();

	// updates the current users param settings
	if ( $my->id == $row->id ) {
		//session_start();
		$_SESSION['session_user_params']= $row->params;
		session_write_close();
	}

	// update the ACL
	if (!$isNew) {
		$query = "SELECT aro_id"
		. "\n FROM #__core_acl_aro"
		. "\n WHERE value = " . (int) $row->id
		;
		$database->setQuery( $query );
		$aro_id = $database->loadResult();

		$query = "UPDATE #__core_acl_groups_aro_map"
		. "\n SET group_id = $row->gid"
		. "\n WHERE aro_id = $aro_id"
		;
		$database->setQuery( $query );
		$database->query() or die( $database->stderr() );
	}

	// Update location admin locations
	if (@is_array($_POST['currLocadminLocs']) || ($_POST['oldLocadminLocs'] && !isset($_POST['currLocadminLocs']))) {
		if (isset($_POST['currLocadminLocs'])) {
			$new = $_POST['currLocadminLocs'];
		} else {
			$new = array();
		}
		$old = explode(',',$_POST['oldLocadminLocs']);
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);

		if (count($add)) {
			$sqlstr = "INSERT INTO #__location_user_xref (`user_id`,`location_id`,`gid`) VALUES (%u,%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$row->id,$item,27);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		if (count($del)) {
			$sqlstr = "DELETE FROM #__location_user_xref WHERE `user_id`=%u AND `location_id`=%u AND `gid`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$row->id,$item,27);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
	}

	// Update instructor locations
	if (@is_array($_POST['currInstLocs']) || ($_POST['oldInstLocs'] && !isset($_POST['currInstLocs']))) {
		if (isset($_POST['currInstLocs'])) {
			$new = $_POST['currInstLocs'];
		} else {
			$new = array();
		}
		$old = explode(',',$_POST['oldInstLocs']);
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);

		if (count($add)) {
			$sqlstr = "INSERT INTO #__location_user_xref (`user_id`,`location_id`,`gid`) VALUES (%u,%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$row->id,$item,26);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		if (count($del)) {
			$sqlstr = "DELETE FROM #__location_user_xref WHERE `user_id`=%u AND `location_id`=%u AND `gid`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$row->id,$item,26);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
	}

	// Update court locations
	if (@is_array($_POST['currCourtadminLocs']) || ($_POST['oldCourtadminLocs'] && !isset($_POST['currCourtadminLocs']))) {
		if (isset($_POST['currCourtadminLocs'])) {
			$new = $_POST['currCourtadminLocs'];
		} else {
			$new = array();
		}
		$old = explode(',',$_POST['oldCourtadminLocs']);
		$add = array_diff($new, $old);
		$del = array_diff($old, $new);

		if (count($add)) {
			$sqlstr = "INSERT INTO #__court_user_xref (`user_id`,`court_id`) VALUES (%u,%u)";
			foreach ($add as $item) {
				$sql = sprintf($sqlstr,$row->id,$item);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}

		if (count($del)) {
			$sqlstr = "DELETE FROM #__court_user_xref WHERE `user_id`=%u AND `court_id`=%u";
			foreach ($del as $item) {
				$sql = sprintf($sqlstr,$row->id,$item);
				$database->setQuery($sql);
				$database->query();
				echo $database->_errorMsg;
			}
		}
	}

	// for new users, email username and password
	if ($isNew) {
		$query = "SELECT email"
		. "\n FROM #__users"
		. "\n WHERE id = " . (int) $my->id
		;
		$database->setQuery( $query );
		$adminEmail = $database->loadResult();

		$subject = _NEW_USER_MESSAGE_SUBJECT;
		$message = sprintf ( _NEW_USER_MESSAGE, $row->name, $mosConfig_sitename,
				A25_Link::to('/administrator/'), $row->username, $pwd );

		if ($mosConfig_mailfrom != "" && $mosConfig_fromname != "") {
			$adminName 	= $mosConfig_fromname;
			$adminEmail = $mosConfig_mailfrom;
		} else {
			$query = "SELECT name, email"
			. "\n FROM #__users"
			// administrator
			. "\n WHERE gid = 25"
			;
			$database->setQuery( $query );
			$admins = $database->loadObjectList();
			$admin 		= $admins[0];
			$adminName 	= $admin->name;
			$adminEmail = $admin->email;
		}

		mosMail( $adminEmail, $adminName, $row->email, $subject, $message, 0, null, null, null, ServerConfig::adminEmailAddress);
	}
  
  // for new users, activate emails for internal messages
  if ($isNew) {
		$query = "INSERT INTO #__messages_cfg"
		. "\n ( user_id, cfg_name, cfg_value )"
		. "\n VALUES ( $row->id, 'mail_on_new', '1' )"
		;
		$database->setQuery( $query );
		$database->query();
  }
	
	fireSaveUser($row);

	if (!$isNew) {
		// if group has been changed
		if ( $originalGid != $row->gid ) {
			// delete user acounts active sessions
			logoutUser( $row->id, 'com_users', 'change' );
		}
	}

	switch ( $task ) {
		case 'apply':
			$msg = 'Successfully Saved changes to User: '. $row->name;
			A25_DI::Redirector()->redirect( 'index2.php?option=com_users&task=editA&hidemainmenu=1&id='. $row->id, $msg );
			break;

		case 'save':
		default:
			$msg = 'Successfully Saved User: '. $row->name;
			A25_DI::Redirector()->redirect( 'index2.php?option=com_users', $msg );
			break;
	}
}

/**
* Cancels an edit operation
* @param option component option to call
*/
function cancelUser( $option ) {
	A25_DI::Redirector()->redirect( 'index2.php?option='. $option .'&task=view' );
}

function removeUsers( $cid, $option ) {
	global $database, $acl, $my;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}

	if ( count( $cid ) ) {
		$obj = new A25_Record_User();
		foreach ($cid as $id) {
			// check for a super admin ... can't delete them
			$groups 	= $acl->get_object_groups( 'users', $id, 'ARO' );
			$this_group = strtolower( $acl->get_group_name( $groups[0], 'ARO' ) );
			if ( $this_group == 'super administrator' && $my->gid != 25 ) {
				$msg = "You cannot delete a Super Administrator";
 			} else if ( $id == $my->id ){
 				$msg = "You cannot delete Yourself!";
 			} else if ( ( $this_group == 'administrator' ) && ( $my->gid == 24 ) ){
 				$msg = "You cannot delete another `Administrator` only `Super Administrators` have this power";
			} else {
				$obj = A25_Record_User::retrieve( $id );
				$count = 2;
				if ( $obj->gid == 25 ) {
					// count number of active super admins
					$query = "SELECT COUNT( id )"
					. "\n FROM #__users"
					. "\n WHERE gid = 25"
					. "\n AND block = 0"
					;
					$database->setQuery( $query );
					$count = $database->loadResult();
				}

				if ( $count <= 1 && $obj->gid == 25 ) {
				// cannot delete Super Admin where it is the only one that exists
					$msg = "You cannot delete this Super Administrator as it is the only active Super Administrator for your site";
				} else {
					// delete user
					$obj = A25_Record_User::retrieve($id);
					$obj->delete();
					$msg = $obj->getError();

					// delete user acounts active sessions
//						logoutUser( $id, 'com_users', 'remove' );
				}
			}
		}
	}

	A25_DI::Redirector()->redirect( 'index2.php?option='. $option, $msg );
}

/**
* Blocks or Unblocks one or more user records
* @param array An array of unique category id numbers
* @param integer 0 if unblock, 1 if blocking
* @param string The current url option
*/
function changeUserBlock( $cid=null, $block=1, $option ) {
	global $database;

	if (count( $cid ) < 1) {
		$action = $block ? 'block' : 'unblock';
		echo "<script> alert('Select an item to $action'); window.history.go(-1);</script>\n";
		exit;
	}

	$cids = implode( ',', $cid );

	$query = "UPDATE #__users"
	. "\n SET block = $block"
	. "\n WHERE id IN ( $cids )"
	;
	$database->setQuery( $query );
	if (!$database->query()) {
		echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		exit();
	}

	// if action is to block a user
	if ( $block == 1 ) {
		foreach( $cid as $id ) {
		// delete user acounts active sessions
			logoutUser( $id, 'com_users', 'block' );
		}
	}

	A25_DI::Redirector()->redirect( 'index2.php?option='. $option );
}

/**
* @param array An array of unique user id numbers
* @param string The current url option
*/
function logoutUser( $cid=null, $option, $task ) {
	global $database, $my;

	if ( is_array( $cid ) ) {
		if (count( $cid ) < 1) {
			A25_DI::Redirector()->redirect( 'index2.php?option='. $option, 'Please select a user' );
		}

		foreach( $cid as $cidA ) {
			$temp = A25_Record_User::retrieve( $cidA );

			// check to see whether a Administrator is attempting to log out a Super Admin
			if ( !( $my->gid == 24 && $temp->gid == 25 ) ) {
				$id[] = $cidA;
			}
		}
		$ids = implode( ',', $id );
	} else {
		$temp = A25_Record_User::retrieve( $cid );

		// check to see whether a Administrator is attempting to log out a Super Admin
		if ( $my->gid == 24 && $temp->gid == 25 ) {
			echo "<script> alert('You cannot log out a Super Administrator'); window.history.go(-1); </script>\n";
			exit();
		}
		$ids = $cid;
	}

	$query = "DELETE FROM #__session"
 	. "\n WHERE userid IN ( $ids )"
 	;
	$database->setQuery( $query );
	$database->query();

	switch ( $task ) {
		case 'flogout':
			A25_DI::Redirector()->redirect( 'index2.php', $database->getErrorMsg() );
			break;

		case 'remove':
		case 'block':
		case 'change':
			return;
			break;

		default:
			A25_DI::Redirector()->redirect( 'index2.php?option='. $option, $database->getErrorMsg() );
			break;
	}
}

function is_email($email){
	$rBool=false;

	if(preg_match("/[\w\.\-]+@\w+[\w\.\-]*?\.\w{1,4}/", $email)){
		$rBool=true;
	}

	return $rBool;
}

function fireSaveUser($row)
{
	foreach (A25_ListenerManager::all() as $listener) {
		if ($listener instanceof A25_ListenerI_AddUserFields) {
			$listener->saveUser($row);
		}
	}
}

function fireAddFilter($mainframe, &$where, &$lists)
{
	foreach (A25_ListenerManager::all() as $listener) {
		if ($listener instanceof A25_ListenerI_ShowUsers) {
			$listener->addFilter($mainframe, $where, $lists);
		}
	}
}