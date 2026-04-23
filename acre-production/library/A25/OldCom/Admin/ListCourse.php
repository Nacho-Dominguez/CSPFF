<?php


class A25_OldCom_Admin_ListCourse
{
	function run( $mainframe, $mosConfig_list_limit, $locs, $option='com_course' ) {

		$limit = intval( $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit ) );
		$limitstart = intval( $mainframe->getUserStateFromRequest( "viewcli{$option}limitstart", 'limitstart', 0 ) );
		$filter_type 	= $mainframe->getUserStateFromRequest( "filter_type{$option}", 'filter_type', null );
		$filter_instructor 	= $mainframe->getUserStateFromRequest( "filter_instructor{$option}", 'filter_instructor', null );
		$filter_location 	= $mainframe->getUserStateFromRequest( "filter_location{$option}", 'filter_location', null );
		$filter_status 	= $mainframe->getUserStateFromRequest( "filter_status{$option}", 'filter_status', null );
		$filter_active 	= (int) $mainframe->getUserStateFromRequest( "filter_active{$option}", 'filter_active', 0 );
		$filter_course_id 	= (int) $mainframe->getUserStateFromRequest( "filter_course_id{$option}", 'filter_course_id', 0 );
		$filter_course_start_date 	= $mainframe->getUserStateFromRequest( "filter_course_start_date{$option}", 'filter_course_start_date', null );

		$where = array();

		$q = Doctrine_Query::create()
				->from('A25_Record_Course c')
				->leftJoin('c.Location l')
				->leftJoin('c.Instructor inst')
				->leftJoin('c.Instructor2 inst2')
				->leftJoin('c.Type ct')
				->leftJoin('c.Status cs')
				->orderBy('c.course_start_date DESC')
				->limit($limit)
				->offset($limitstart)
				;

		if ( $filter_type ) {
			$q->andWhere('c.course_type_id= ?', $filter_type);
		}

		if ( $filter_instructor ) {
			$q->andWhere('(c.instructor_id = ? OR c.instructor_2_id = ?)',
					array($filter_instructor, $filter_instructor));
		}

		if ( $filter_location ) {
			$q->andWhere('c.location_id = ?', $filter_location);
		}

		if ( $filter_status ) {
			$q->andWhere('c.status_id = ?', $filter_status);
		}

		if ( $filter_course_start_date ) {
			$date = date('Y-m-d', strtotime($filter_course_start_date));
			$q->andWhere('c.course_start_date > ?', $date . ' 00:00:00');
			$q->andWhere('c.course_start_date < ?', $date . ' 23:59:59');
		}

		if ( $filter_active <> 0 ) {
			$q->andWhere('c.published = ?', ($filter_active == -1 ? 0 : 1));
		}

		if ( $filter_course_id <> 0 ) {
			$q->andWhere('c.course_id = ?', $filter_course_id);
		}

		if ( @$locs[0] != 'all' ) {
			$q->andWhereIn('c.location_id', $locs);
		}

		// get the total number of records
		$total = $q->count();
		if ($total < $limitstart) { $limitstart = 0; }

		require_once( ServerConfig::webRoot . '/administrator/includes/pageNavigation.php' );
		$pageNav = new mosPageNav( $total, $limitstart, $limit );
		$courses = $q->execute();
		
		if ($courses) {
			$enrolled = array();
			foreach ($courses as $course) {
				$numberOfEnrolledStudents = 0;
				$numberOfEnrolledStudents += $course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_registered);
				$numberOfEnrolledStudents += $course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_student);
				$numberOfEnrolledStudents += $course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_completed);
				$numberOfEnrolledStudents += $course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_pending);
				$numberOfEnrolledStudents += $course->getEnrollmentStatusCount(A25_Record_Enroll::statusId_failed);
				$enrolled[$course->course_id] = $numberOfEnrolledStudents;
			}

			$lists['enrolled'] = $enrolled;
		}

		// build list of types
		$lists['filter_type'] = A25_SelectListGenerator::generateCourseTypeSelectList('filter_type', $filter_type);

		// build list of status
		$lists['filter_status'] = A25_SelectListGenerator::generateCourseStatusSelectList('filter_status', $filter_status);
		
		// build list of active
		$active = array();
		$active[] = mosHTML::makeOption(0,'- Select Active -');
		$active[] = mosHTML::makeOption(-1,'- Show Inactive -');
		$active[] = mosHTML::makeOption(1,'- Show Active -');
		$javascript = '';
		$lists['filter_active'] = mosHTML::selectList( $active, 'filter_active', $javascript, 'value', 'text', $filter_active);

		// build list of instructors
		$insts = array();
		$insts[] = mosHTML::makeOption('','- Select Instructor -');
		if ( @$locs[0] == 'all') {
			$whereloc = '';
		} else {
			$whereloc = "\n WHERE x.`location_id` IN (" . implode(',',$locs) . ") ";
		}
		$sql = "SELECT DISTINCT u.`id` AS value, CONCAT(u.`name`,IF(u.block,' (Inactive)','')) AS text"
			. "\n FROM #__users u"
			. "\n LEFT JOIN #__location_user_xref x ON (u.id=x.user_id)"
			. $whereloc
			. "\n ORDER BY u.`name`"
			;
		A25_DI::DB()->setQuery($sql);
		$insts = array_merge($insts,A25_DI::DB()->loadObjectList());
		$javascript = '';
		$lists['filter_instructor'] = mosHTML::selectList( $insts, 'filter_instructor', $javascript, 'value', 'text', $filter_instructor);

		// build list of locations
		$locations = array();
		$locations[] = mosHTML::makeOption('','- Select Location -');
		if ( @$locs[0] == 'all') {
			$whereloc = '';
		} else {
			$whereloc = "AND `location_id` IN (" . implode(',',$locs) . ") ";
		}

		$sql = "SELECT `location_id` AS value, `location_name` AS text FROM #__location WHERE `is_location`=1 " . $whereloc . "ORDER BY `location_name`;";
		A25_DI::DB()->setQuery($sql);
		$locations = array_merge($locations,A25_DI::DB()->loadObjectList());
		$javascript = '';
		$lists['filter_location'] = mosHTML::selectList( $locations, 'filter_location', $javascript, 'value', 'text', $filter_location);

		$lists['filter_course_start_date'] = $filter_course_start_date ? date("m/d/Y",strtotime($filter_course_start_date)) : '';
		$lists['filter_course_id'] = $filter_course_id ? $filter_course_id : '';

		A25_OldCom_Admin_ListCourseHtml::listCourse($courses, $pageNav, $option, $lists, $my);
	}
}
?>
