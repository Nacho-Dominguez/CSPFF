<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

global $mosConfig_absolute_path, $acl, $mainframe, $option, $database, $my;

if (!$acl->acl_check( 'administration', 'manage', 'users', $my->usertype, 'components', 'com_users' )) {
	A25_DI::Redirector()->redirect( 'index2.php', _NOT_AUTH );
}

require_once(dirname(__FILE__) . '/../../../autoload.php');
require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );
require_once( $mosConfig_absolute_path . '/administrator/components/com_stats/stats.config.php' );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	A25_DI::Redirector()->redirect( 'index2.php', 'You are not authorized to view any statistics.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );

$filter = new A25_ReportFilter();
$filter->from = $mainframe->getUserStateFromRequest( "f_from{$option}", 'f_from', null );
$filter->to = $mainframe->getUserStateFromRequest( "f_to{$option}", 'f_to', null );
$filter->lid = (int) $mainframe->getUserStateFromRequest( "f_lid{$option}", 'f_lid', null );
$filter->instructorId = (int) $mainframe->getUserStateFromRequest( "f_instructorId{$option}", 'f_instructorId', null );
$filter->per = (boolean) $mainframe->getUserStateFromRequest( "f_per{$option}", 'f_per', 1 );
fireSetLocationFilter($filter, $mainframe, $option);

if (!($filter->from && $filter->to)) {
	$filter->from = mktime(0, 0, 0, date("m"), 1, date("Y"));
	$filter->to = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
} else {
	$filter->from = strtotime($filter->from);
	//add in almost a full day
	$filter->to = strtotime($filter->to) + 86399;
}

$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );


switch ($task) {

	case "completedbycourt":
		$report = new A25_Report_CompletedByCourt($filter, $limit, $limitstart);
		$report->run();
		break;
    
	case "completedstudents":
		$report = new A25_Report_CompletedStudents($filter, $limit, $limitstart);
		$report->run();
		break;

	case "course":
		ADMIN_stats::courseStats( $filter, $option );
		break;

	case "courses":
		$report = new A25_Report_Course($limit, $limitstart);
		$report->run();
		break;

	case "court":
		$report = new A25_Report_Court($filter, $limit, $limitstart);
		$report->run();
		break;

	case "courtSurchargeCollected":
		$report = new A25_Report_CollectedCourtSurcharges($filter, $limit, $limitstart);
		$report->run();
		break;

	case "dmv":
		$report = new A25_Report_Dmv($filter, $limit, $limitstart);
		$report->run();
		break;

	case "enrollment":
		$report = new A25_Report_Enrollment($limit, $limitstart);
		$report->run();
		break;

	case "location":
		A25_LocationStatsReporter::locationStats($filter, $database, $locs);
		break;

	case "losing":
		ADMIN_stats::losingStats( $filter, $option );
		break;

	case "marketing":
		ADMIN_stats::marketingStats( $filter, $option );
		break;

	case "payment":
		ADMIN_stats::paymentStats( $filter, $option );
		break;

	case "creditTypeStats":
		ADMIN_stats::creditTypeStats( $filter, $option );
		break;

	case "refundByType":
		$report = new A25_Report_RefundSummary($filter, $limit, $limitstart);
		$report->run();
		break;

	case "fees":
		$report = new A25_Report_Fee($limit, $limitstart);
		$report->run();
		break;

	case "refund":
		$report = new A25_Report_Refund($filter, $limit, $limitstart);
		$report->run();
		break;

	case "uncategorizedRefund":
       $report = new A25_Report_Refund_Uncategorized($filter, $limit, $limitstart);
       $report->run();
       break;

  case "income":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_Income($limit, $limitstart);
		$report->run();
    break;
    
  case "upcoming_course_revenue":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_UpcomingCourseRevenue($limit, $limitstart);
		$report->run();
    break;
    
  case "student_balances":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_StudentBalances($limit, $limitstart);
		$report->run();
    break;

	case "cpanel":
	default:
		$redirector = new A25_Redirector();
		$redirector->redirect('reports','',301);
		break;
}

class ADMIN_stats {

	/**
	 * Lists all courses in alphabetical order, with the option to filter.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param  object $filter
	 * @return void
	 */
	function courseStats( $filter, $option ) {
		global $mainframe, $mosConfig_list_limit, $locs;
		A25_CourseRevenueReport::run($filter, $option, $mainframe,
				$mosConfig_list_limit, $locs);
	}


	/**
	 * Lists all losings courses in chronological order, with the option to filter by date.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param  object $filter
	 * @return void
	 */
	function losingStats( $filter, $option ) {
		global $database, $my, $mainframe, $mosConfig_list_limit, $locs;

		$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );

		$stats = new losingStats( $filter, $locs );
		$stats->count();

		if ($stats->total < $limitstart) { $limitstart = 0; }
		require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $stats->total, $limitstart, $limit );

		$stats->load('data', $pageNav);

		//print_r($stats->data); die();
		//$stats->process();

		$lists = array();

		$where = array();

		if ($locs[0] != 'all') {
			$where[] = "l.location_id IN (" . implode(",",$locs) . ")";
		}

		// build list of locs
		$locs = array();
		$locs[] = mosHTML::makeOption('','(show all)');
		$sql = "SELECT `location_id` AS value, `location_name` AS text FROM #__location"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY `location_name`;";
		$database->setQuery($sql);
		$locs = array_merge($locs,$database->loadObjectList());
		$lists['f_lid'] = mosHTML::selectList( $locs, 'f_lid', '', 'value', 'text', $filter->lid);

		HTML_stats::losingStats( $stats, $lists, $pageNav, $limit, $limitstart);
	}


	/**
	 * Lists marketings statistics, with the option to filter by date.
	 * @author Christiaan van Woudenberg
	 * @version September 11, 2006
	 *
	 * @param  object $filter
	 * @return void
	 */
	function marketingStats( $filter, $option ) {
		global $database, $my, $mainframe, $mosConfig_list_limit, $locs;

		$stats = new stdClass;
		$stats->filter = $filter;
		$stats = new marketingStats( $filter, $locs );

		$stats->summary();

		//print_r($stats->data); die();
		//$stats->process();

		$lists = array();

		$p = array();
		$p[] = mosHTML::makeOption(0,'Absolute Values');
		$p[] = mosHTML::makeOption(1,'Percentages');
		$lists['f_per'] = mosHTML::selectList( $p, 'f_per', 'class="inputbox"',
				'value', 'text', $filter->per);

		HTML_stats::marketingStats( $stats, $lists );
	}


	/**
	 * Lists all payments in received order, with the option to filter by date.
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @param  object $filter
	 * @return void
	 */
	function paymentStats( $filter, $option ) {
		global $database, $my, $mainframe, $mosConfig_list_limit, $locs;

		$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );

		$stats = new paymentStats( $filter, $locs );
		$stats->count();

		if ($stats->total < $limitstart) { $limitstart = 0; }
		require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $stats->total, $limitstart, $limit );

		$stats->load('data', $pageNav);

		//print_r($stats->data); die();
		//$stats->process();

		$lists = array();

		/*
		// build list of states
		$states = array();
		$states[] = mosHTML::makeOption('','(show all)');
		$sql = "SELECT `state_code` AS value, `state_name` AS text FROM #__us_state ORDER BY `state_name`;";
		$database->setQuery($sql);
		$states = array_merge($states,$database->loadObjectList());
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['f_state'] = mosHTML::selectList( $states, 'f_state', $javascript, 'value', 'text', $filter->state);
		*/

		HTML_stats::paymentStats( $stats, $lists, $pageNav );
	}


	/**
	 * Lists all credit type payments in received order, with the option to filter by date.
	 * @author Garey hoffman
	 * @version February 19, 2006
	 *
	 * @param  object $filter
	 * @return void
	 */
	function creditTypeStats( $filter, $option ) {
		global $database, $my, $mainframe, $mosConfig_list_limit, $locs;


		$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );

		$filter->credit_type = $mainframe->getUserStateFromRequest( "filter_credit_type{$option}", 'filter_credit_type', null );

		$stats = new creditTypeStats( $filter, $locs );
		$stats->count();

		if ($stats->total < $limitstart) { $limitstart = 0; }
		require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $stats->total, $limitstart, $limit );

		$stats->load('data', $pageNav);

		//print_r($stats->data); die();
		//$stats->process();

		$lists = array();

		// This section added 2008-01-02 by Thomas Albright
		// build list of credit_types:
		$credit_types = array();
		$credit_types[] = mosHTML::makeOption('','(show all)');
		$sql = "SELECT `credit_type_id` AS value, `credit_type_name` AS text FROM #__credit_type`;";
		$database->setQuery($sql);
		$credit_types = array_merge($credit_types,$database->loadObjectList());
		$lists['filter_credit_type'] = mosHTML::selectList( $credit_types, 'filter_credit_type', null, 'value', 'text', $filter->credit_type);
		// End of added section




		/*
		// build list of states
		$states = array();
		$states[] = mosHTML::makeOption('','(show all)');
		$sql = "SELECT `state_code` AS value, `state_name` AS text FROM #__us_state ORDER BY `state_name`;";
		$database->setQuery($sql);
		$states = array_merge($states,$database->loadObjectList());
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['f_state'] = mosHTML::selectList( $states, 'f_state', $javascript, 'value', 'text', $filter->state);
		*/

		HTML_stats::creditTypeStats( $stats, $lists, $pageNav );
	}
}

function fireSetLocationFilter($filter, $mainframe, $option)
{
	foreach (A25_ListenerManager::all() as $listener) {
		if ($listener instanceof A25_ListenerI_LocationStats) {
			$listener->setLocationFilter($filter, $mainframe, $option);
		}
	}
}
