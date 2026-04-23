<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 */
class A25_OldCom_Admin_ListPay {
    /**
     *
     * @param <type> $from
     * @param <type> $to
     * @param string $option - This is the name of the component.  With this
     * function, it will usually be 'com_pay'.
     * @param array $locs
     * @param <type> $mainframe
     * @param <type> $mosConfig_list_limit
     */
    public static function run( $from, $to, $option, $locs, $mainframe, $mosConfig_list_limit)
    {
        $limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );

		$filter = new stdClass;
		$filter->from = $from;
		$filter->to = $to;
		$filter->name 	= $mainframe->getUserStateFromRequest( "filter_name{$option}", 'filter_name', null );
		$filter->dob 	= $mainframe->getUserStateFromRequest( "filter_dob{$option}", 'filter_dob', null );
		$filter->address 	= $mainframe->getUserStateFromRequest( "filter_address{$option}", 'filter_address', null );
		$filter->phone 	= $mainframe->getUserStateFromRequest( "filter_phone{$option}", 'filter_phone', null );
		$filter->check_no 	= $mainframe->getUserStateFromRequest( "filter_check_no{$option}", 'filter_check_no', null );
		$filter->pay_type 	= $mainframe->getUserStateFromRequest( "filter_pay_type{$option}", 'filter_pay_type', null );
		$filter->paid_by 	= $mainframe->getUserStateFromRequest( "filter_paid_by{$option}", 'filter_paid_by', null );
		$filter->pay_date 	= $mainframe->getUserStateFromRequest( "filter_pay_date{$option}", 'filter_pay_date', null );
		$filter->taken_by 	= $mainframe->getUserStateFromRequest( "filter_taken_by{$option}", 'filter_taken_by', null );

		$where = array();

		if ( !empty($_GET['id']) ) {
		  $where[] = "p.`student_id`='" . $_GET['id'] . "'";
		}

		if ( $filter->from ) {
			$where[] = "p.`created`>='" . date('Y-m-d',$filter->from) . " 00:00:00'";
		}

		if ( $filter->to ) {
			$where[] = "p.`created`<='" . date('Y-m-d',$filter->to) . " 23:59:59'";
		}

		if ( $filter->name ) {
			$where[] = "(s.`first_name` LIKE '%$filter->name%' OR s.`last_name` LIKE '%$filter->name%' OR s.`email` LIKE '%$filter->name%')";
		}

		if ( $filter->dob ) {
			$where[] = "s.`date_of_birth`='" . date("Y-m-d",strtotime($filter->dob)) . "'";
		}

		if ( $filter->address ) {
			$where[] = "(s.`address_1` LIKE '%$filter->address%' OR s.`address_2` LIKE '%$filter->address%' OR s.`city` LIKE '%$filter->address%' OR s.`zip` LIKE '%$filter->address%')";
		}

		if ( $filter->phone ) {
			$where[] = "s.`home_phone`='$filter->phone'";
		}

		if ( $filter->check_no ) {
			$where[] = "p.`check_number`='$filter->check_no'";
		}

		if ( $filter->pay_type ) {
			$where[] = "p.`pay_type_id`='$filter->pay_type'";
		}

		if ( $filter->paid_by ) {
			$where[] = "p.`paid_by_name`='$filter->paid_by'";
		}

		if ( $filter->pay_date ) {
			$where[] = "p.`created`='" . date("Y-m-d",strtotime($filter->pay_date)) . "'";
		}

		if ( $filter->taken_by ) {
			$where[] = "u.`name` LIKE '%$filter->taken_by%'";
		}

		if ( @$locs[0] != 'all' ) {
			$where[] = "c.location_id IN (" . implode(',',$locs) . ")";
		}

		// get the total number of records
		$query = "SELECT COUNT(*)"
		. "\n FROM #__pay p"
		. "\n LEFT JOIN #__student_course_xref x USING (`xref_id`)"
		. "\n LEFT JOIN #__student s ON (x.`student_id` = s.`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__users u ON (p.`created_by` = u.`id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		;
		A25_DI::DB()->setQuery( $query );
		$total = A25_DI::DB()->loadResult();
		if ($total < $limitstart) { $limitstart = 0; }

		require_once( dirname(__FILE__) . '/../../../../administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $total, $limitstart, $limit );

		$sql = "SELECT p.*,DATE_FORMAT(p.`created`,\"%Y-%m-%d\") AS `pay_created`,"
		. "\n s.*, pt.`pay_type_name`,u.`name` AS `taken_by_name`,"
		. "\n DATE_FORMAT(c.`course_start_date`,\"%Y-%m-%d\") AS course_start_date,"
		. "\n l.`location_name`, cs.credit_id, ct.credit_type_name"
		. "\n FROM #__pay p"
		. "\n LEFT JOIN #__student_course_xref x USING (`xref_id`)"
		. "\n LEFT JOIN #__student s ON (x.`student_id` = s.`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. "\n LEFT JOIN #__pay_type pt ON (p.`pay_type_id` = pt.`pay_type_id`)"
		. "\n LEFT JOIN #__credits cs ON (cs.`pay_id` = p.`pay_id`)"
		. "\n LEFT JOIN #__credit_type ct ON (ct.`credit_type_id` = cs.`credit_type_id`)"
		. "\n LEFT JOIN #__users u ON (p.`created_by` = u.`id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY p.`created`"
		. "\n LIMIT $pageNav->limitstart, $pageNav->limit";
		A25_DI::DB()->setQuery( $sql );
		$rows = A25_DI::DB()->loadObjectList();
		echo A25_DI::DB()->_errorMsg;
		//echo str_replace('#_','jos',$sql);

		// build list of pay_types
		$pay_types = array();
		$pay_types[] = mosHTML::makeOption('','(show all)');
		$sql = "SELECT `pay_type_id` AS value, `pay_type_name` AS text FROM `#__pay_type`;";
		A25_DI::DB()->setQuery($sql);
		$pay_types = array_merge($pay_types,A25_DI::DB()->loadObjectList());
		$javascript = 'onchange="document.adminForm.submit();"';
		$lists['filter_pay_type'] = mosHTML::selectList( $pay_types, 'filter_pay_type', $javascript, 'value', 'text', $filter->pay_type);

		A25_OldCom_Admin_ListPayHtml::listPay( $rows, $pageNav, $option, $lists, $filter);
    }
}
?>
