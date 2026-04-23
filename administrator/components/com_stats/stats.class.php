<?php
/**
* Alive At 25
* @version $Id$
* @package Alive At 25
* @subpackage stats.class.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

require_once(dirname(__FILE__) . '/../../../autoload.php');

/**
 * Date format for displaying course start times
 */
defined( '_MYSQL_DATE_SHORT' ) or DEFINE('_MYSQL_DATE_SHORT','%b %d, %Y');

/**
 * Date format for displaying course start times
 */
defined( '_MYSQL_TIME_SHORT' ) or DEFINE('_MYSQL_TIME_SHORT','%h:%i %p');

/**
 * Date format for displaying dates in XLS export
 */
defined( '_MYSQL_DATE_XLS' ) or DEFINE('_MYSQL_DATE_XLS','%m/%d/%Y');


/**
 * Main Stats class
 * @author Christiaan van Woudenberg
 * @version September 10, 2006
 *
 * @return void
 */
class stats {
	/** @var array */
	var $locs = null;
	/** @var object */
	var $filter = null;
	/** @var array */
	var $data = null;
	/** @var int */
	var $count = null;
	/** @var array */
	var $statistics = null;

	/**
	 * Instantiates the stats class
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @return boolean
	 */
	function stats( $filter, $locs = array() ) {
		$this->filter = $filter;
		$this->locs = $locs;
	}


	/**
	 * Adds object elements from one object to another
	 * @author Christiaan van Woudenberg
	 * @version September 11, 2006
	 *
	 * @return boolean
	 */
	function add( $from, $to ) {
		foreach ($from as $key => $val) {
			$to->$key = $val;
		}
		return true;
	}

	protected function fetchReasonForEnrollmentStats(database $database, $where,
			$includeRevenueInfo = false)
	{
    $where = A25_StatsQueryBuilder::addWhereEnrollmentIsActive($where);
		$sql = A25_StatsQueryBuilder::ReasonEnrolled($where,
				$includeRevenueInfo);

		$database->setQuery($sql);
		$reason = $database->loadObjectList();
		return $reason;
	}
}


/**
 * Course revenue stats class
 * @author Christiaan van Woudenberg
 * @version September 11, 2006
 *
 * @return void
 */
class courseStats extends stats {

	/**
	 * Loads course revenue statistics for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 11, 2006
	 *
	 * @return boolean
	 */
	function count(database $database) {
		$where = array();


		if ( $this->filter->from ) {
			$where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		// get the total number of records
		$query = "SELECT c.`course_id`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x ON (c.`course_id` = x.`course_id`)"
		. "\n LEFT JOIN #__pay p ON (x.`xref_id` = p.`xref_id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY c.`course_id`"
		;
		$database->setQuery( $query );
		$courses = $database->loadResultArray();
		echo $database->_errorMsg;
		$this->total = count($courses);
		unset($courses);
	}


	/**
	 * Loads course statistics for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param string $mode
	 * @param object $pageNav
	 * @return boolean
	 */
	function load( database $database, $mode = 'data', $pageNav = array() ) {
		$where = array();

		if ( $this->filter->from ) {
			$where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		switch ( $mode ) {
			case 'export':
				$sql = "SELECT c.`course_id` AS `Course ID`,"
				. "\n l.`location_name` AS `Location Name`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_DATE_SHORT . "') AS `Course Date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_TIME_SHORT . "') AS `Course Time`,"
				. "\n IF(c.`instructor_id`>0 AND c.`instructor_2_id`>0,2,IF(c.`instructor_id`>0,1,0)) AS `Number of Instructors`,"
				. "\n SUM(IF(x.`status_id`=1,1,0)) AS `Number Registered`,"
				. "\n SUM(IF(x.`status_id`=2,1,0)) AS `Number Student`,"
				. "\n SUM(IF(x.`status_id`=3,1,0)) AS `Number Completed`,"
				. "\n SUM(IF(x.`status_id`=4,1,0)) AS `Number Cancelled`,"
				. "\n SUM(IF(x.`status_id`=5,1,0)) AS `Number No Show`,"
				. "\n SUM(IF(x.`status_id`=6,1,0)) AS `Number Unknown`,"
				. "\n SUM(IF(x.`status_id`=7,1,0)) AS `Number Pending`,"
				. "\n SUM(IF(x.`court_id`>0,1,0)) AS `Number Court Ordered`,"
				. "\n SUM(IF(x.`court_id`=0 OR x.`court_id` IS NULL,1,0)) AS `Number Voluntary`,"
				. "\n SUM(IF(p.`amount`>0,1,0)) AS `Number Paid`,"
				. "\n CONCAT('$',SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200)) AS `Gross Profit`"
				;
				break;

			case 'summary':
			default:
				$sql = "SELECT c.`course_id`,"
				. "\n c.`instructor_id`,"
				. "\n c.`instructor_2_id`,"
				. "\n IF(c.`instructor_id`>0 AND c.`instructor_2_id`>0,2,IF(c.`instructor_id`>0,1,0)) AS `num_instructors`,"
				. "\n SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200) AS `gross_revenue`"
				;
				break;

			case 'data':
			default:
				// Most of this data is no longer used.  Eventually, we will
				// remove this query altogether.
				$sql = "SELECT c.`course_id`,"
				. "\n l.`location_name`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_DATE_SHORT . "') AS `course_date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_TIME_SHORT . "') AS `course_time`,"
				. "\n IF(c.`instructor_id`>0 AND c.`instructor_2_id`>0,2,IF(c.`instructor_id`>0,1,0)) AS `num_instructors`,"
				. "\n SUM(IF(x.`status_id`=1,1,0)) AS `num_R`,"
				. "\n SUM(IF(x.`status_id`=2,1,0)) AS `num_S`,"
				. "\n SUM(IF(x.`status_id`=3,1,0)) AS `num_C`,"
				. "\n SUM(IF(x.`status_id`=4,1,0)) AS `num_X`,"
				. "\n SUM(IF(x.`status_id`=5,1,0)) AS `num_N`,"
				. "\n SUM(IF(x.`status_id`=6,1,0)) AS `num_U`,"
				. "\n SUM(IF(x.`status_id`=7,1,0)) AS `num_P`,"
				. "\n SUM(IF(x.`court_id`>0,1,0)) AS `num_court_ordered`,"
				. "\n SUM(IF(x.`court_id`=0 OR x.`court_id` IS NULL,1,0)) AS `num_voluntary`,"
				. "\n SUM(IF(p.`amount`>0,1,0)) AS `num_paid`,"
				. "\n CONCAT('$',SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200)) AS `gross_revenue`"
				;
				break;
		}

		$sql .= "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x ON (c.`course_id` = x.`course_id`)"
		. "\n LEFT JOIN #__pay p ON (x.`xref_id` = p.`xref_id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY c.`course_id`"
		. "\n ORDER BY c.`course_start_date`"
		. ( $mode == 'data' ? "\n LIMIT $pageNav->limitstart, $pageNav->limit" : "" )
		;
		$database->setQuery( $sql );

		switch ( $mode ) {
			case 'export':
				$this->data = $database->loadResultList();
				break;
			case 'data':
			case 'summary':
			default:
				$this->data = $database->loadObjectList();
				break;
		}

		echo $database->_errorMsg;
		//echo str_replace('#_','jos',$sql);

		return true;
	}


	/**
	 * Calculates course revenue summary statistics for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @return boolean
	 */
	function summary(database $db) {
		$this->load($db,'summary');
		$s = new stdClass();
		$s->numCourses = 0;
		$s->numLosingCourses = 0;
		$s->totalLosingCost = 0;
		$s->totalGrossProfit = 0;
		$s->totalRevenue = 0;

		foreach ($this->data as $row) {
			$course = A25_Record_Course::retrieve( $row->course_id);
			$s->numCourses++;
			$gross = $course->getGrossRevenue();
			$instructorFees = $course->getInstructorFees();
			$profit = $gross - $instructorFees;
			if ($profit < 0) {
				$s->numLosingCourses++;
				$s->totalLosingCost += $profit;
			}
			$s->totalGrossProfit += $profit;
			$s->totalRevenue += $gross;
		}

		// Both of these lines are equivalent:
		$s->totalInstructorPayroll = $s->totalRevenue - $s->totalGrossProfit;
		
		$this->statistics = $s;
		return true;
	}
}


/**
 * Location stats class
 * @author Christiaan van Woudenberg
 * @version September 11, 2006
 *
 * @return void
 */
class locationStats extends stats {
  private $where;
  function __construct($filter, $locs) {
    parent::__construct($filter, $locs);
    
    $this->where = array();
    
		if ( $this->filter->from ) {
			$this->where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$this->where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->filter->lid ) {
			$this->where[] = "c.`location_id`='" . (int) $this->filter->lid . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$this->where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		if ( $this->filter->instructorId ) {
			$this->where[] = '(c.`instructor_id`=' . $this->filter->instructorId . ' OR c.`instructor_2_id`=' . $this->filter->instructorId . ')';
		}
    
    $this->where = self::fireAddWhereClause($this->filter, $this->where);
  }

	/**
	 * Calculates location statistics for the current filter set.  These
	 * statistics include cancelled enrollments.  That is pry a problem.  Some
	 * students are double-counted, if they cancelled an enrollment.
	 *
	 * @paray database $database - a Joomla database object
	 * @param array $insts - array of instructor Ids to display results of
	 * @return boolean
	 */
	function summary($db) {
		$s = new stdClass();

    $age = $this->fetchAgeStats();

		$total = 0;
		foreach ($age as $a) {
			$total += $a->count;
		}
		$s->age->data = $age;
		$s->age->total = $total;

		$reason = $this->fetchReasonForEnrollmentStats($db, $this->where);

		$total = 0;
		foreach ($reason as $r) {
			$total += $r->count;
		}
		$s->reason->data = $reason;
		$s->reason->total = $total;
    
		$course = $this->fetchCourseStatusStats();

		$total = 0;
		foreach ($course as $r) {
			$total += $r->count;
		}
		$s->course->data = $course;
		$s->course->total = $total;

		$enroll = $this->fetchEnrollStatusStats();

		$total = 0;
		foreach ($enroll as $r) {
			$total += $r->count;
		}
		$s->enroll->data = $enroll;
		$s->enroll->total = $total;

		$gender = $this->fetchGenderStats();

		$total = 0;
		foreach ($gender as $g) {
			$total += $g->count;
		}
		$s->gender->data = $gender;
		$s->gender->total = $total;

		$inst = $this->fetchInstructorStats();

		$total_pri = 0;
		$total_sec = 0;
		foreach ($inst as $g) {
			$total_pri += $g->count_pri;
			$total_sec += $g->count_sec;
		}
		$s->inst->data = $inst;
		$s->inst->total_pri = $total_pri;
		$s->inst->total_sec = $total_sec;
		$s->inst->total = $total_pri+$total_sec;

		$rev = $this->fetchCourseRevenueStats();

		$total_students = 0;
		$total_capacity = 0;
		$total_profit = 0;
		$max_profit = 0;
		$min_profit = 0;
		foreach ($rev as $r) {
			$total_students += $r->num_students;
			$total_capacity += $r->course_capacity;
			$total_profit += $r->gross_revenue;
			$max_profit = ($r->gross_revenue > $max_profit ? $r->gross_revenue : $max_profit);
			$min_profit = ($r->gross_revenue < $min_profit ? $r->gross_revenue : $min_profit);
		}
		$s->rev->data = $rev;
		$s->rev->total_students = $total_students;
		$s->rev->total_capacity = $total_capacity;
		$s->rev->total_profit = $total_profit;
		$s->rev->min_profit = $min_profit;
		$s->rev->min_profit = $min_profit;
		$s->rev->max_abs_profit = max(abs($max_profit),abs($min_profit));

		$this->statistics = $s;
		return true;
	}
  
  protected function fetchAgeStats()
  {
    $where = A25_StatsQueryBuilder::addWhereEnrollmentIsActive($this->where);
    $db = A25_DI::DB();
    $sql = A25_StatsQueryBuilder::age($where);

		$db->setQuery($sql);
		$age = $db->loadObjectList('age');
		echo $db->_errorMsg;
    return $age;
  }
  
  protected function fetchCourseStatusStats()
  {
    $db = A25_DI::DB();
		$sql = "SELECT c.`status_id`,s.`status_name`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. "\n LEFT JOIN #__course_status s ON (c.`status_id` = s.`status_id`)";
    $sql = self::fireJoinTable($sql);
		$sql .= ( count( $this->where ) ? "\n WHERE " . implode( ' AND ', $this->where ) : "" )
		. "\n GROUP BY c.`status_id`"
		. "\n ORDER BY c.`status_id`";

		$db->setQuery($sql);
		$course = $db->loadObjectList();
		echo $db->_errorMsg;
    return $course;
  }
  
  protected function fetchEnrollStatusStats()
  {
    $db = A25_DI::DB();
		$sql = "SELECT e.`status_id`,e.`status_name`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. "\n INNER JOIN #__student_course_xref x ON (c.`course_id` = x.`course_id`)"
		. "\n LEFT JOIN #__enroll_status e ON (x.`status_id` = e.`status_id`)";
    $sql = self::fireJoinTable($sql);
		$sql .= ( count( $this->where ) ? "\n WHERE " . implode( ' AND ', $this->where ) : "" )
		. "\n GROUP BY x.`status_id`"
		. "\n ORDER BY x.`status_id`";

		$db->setQuery($sql);
		$enroll = $db->loadObjectList();
		echo $db->_errorMsg;
    return $enroll;
  }
  
  protected function fetchGenderStats()
  {
    $where = A25_StatsQueryBuilder::addWhereEnrollmentIsActive($this->where);
    $db = A25_DI::DB();
    $sql = A25_StatsQueryBuilder::gender($where);

		$db->setQuery($sql);
		$gender = $db->loadObjectList('gender');
		echo $db->_errorMsg;
    return $gender;
  }
  
  protected function fetchInstructorStats()
  {
    $where = $this->where;
    $where[] = 'c.`status_id` != ' . A25_Record_Course::statusId_Cancelled;
    $db = A25_DI::DB();
		$sql = "SELECT u.`name`,SUM(IF(u.`id`=c.`instructor_id`,1,0)) AS `count_pri`,SUM(IF(u.`id`=c.`instructor_2_id`,1,0)) AS `count_sec`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__users u ON (c.`instructor_id` = u.`id` OR c.`instructor_2_id`=u.`id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)";
    $sql = self::fireJoinTable($sql);
		$sql .= ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY u.`id`"
		. "\n ORDER BY u.`name`";

		$db->setQuery($sql);
		$inst = $db->loadObjectList();
		echo $db->_errorMsg;
    return $inst;
  }
  
  protected function fetchCourseRevenueStats()
  {
    $db = A25_DI::DB();
    $sql = $this->courseRevenueQuery();

		$db->setQuery($sql);
		$rev = $db->loadObjectList();
		echo $db->_errorMsg;
    return $rev;
  }
  
    protected function courseRevenueQuery()
    {
        $strategy = new \AppDevl\QueryStrategy\MysqlStringStrategy(
            \Acre\A25\Query\CourseJoiner::doctrineToSqlTranslations());
        $joiner = new \Acre\A25\Query\CourseJoiner($strategy);
        $gross_revenue_query = new Acre\A25\Query\CourseGrossRevenueQuery(
            $joiner, $strategy);
        $sql = "SELECT c.`course_id`,"
        . "\n l.`location_id`,"
        . "\n l.`location_name`,"
        . "\n cs.`status_name` AS `course_status`,"
        . "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_DATE_SHORT . "') AS `course_date`,"
        . "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_TIME_SHORT . "') AS `course_time`,"
        . "\n SUM(IF(" . A25_Record_Enroll::elligibleForCourseRevenue('e')
        . ",IF(" . A25_Record_OrderItem::elligibleForCourseRevenue('i')
        . ",1,0),0)) AS `num_students`,"
        . "\n c.`course_capacity`";
        $sql = $gross_revenue_query->select($sql);
        $sql = $gross_revenue_query->from($sql);
        $sql .= "\n LEFT JOIN #__student s USING (`student_id`)"
        . "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
        . "\n LEFT JOIN #__course_status cs ON (c.`status_id` = cs.`status_id`)"
        . "\n LEFT JOIN #__pay p ON (e.`xref_id` = p.`xref_id`)";
        $sql = self::fireJoinTable($sql);
        $sql .= ( count( $this->where ) ? "\n WHERE " . implode( ' AND ', $this->where ) : "" );
        $sql = $gross_revenue_query->groupBy($sql);
        $sql .= "\n ORDER BY c.`course_start_date`";
        return $sql;
    }
  
  private static function fireJoinTable($query)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_LocationStats) {
        $query = $listener->joinTable($query);
      }
    }
    return $query;
  }
  
  private static function fireAddWhereClause($filter, $where)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_LocationStats) {
        $where = $listener->addWhereClause($filter, $where);
      }
    }
    return $where;
  }
}

/**
 * Losing courses stats class
 * @author Christiaan van Woudenberg
 * @version September 10, 2006
 *
 * @return void
 */
class losingStats extends stats {

	/**
	 * Loads losing courses report for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @return boolean
	 */
	function count() {
		global $database;

		$where = array();
		
		$where[] = "c.`status_id` <> " . A25_Record_Course::statusId_Cancelled;

		if ( $this->filter->from ) {
			$where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->filter->lid ) {
			$where[] = "c.`location_id`='" . (int) $this->filter->lid . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		// get the total number of records
		$query = "SELECT c.`course_id`,"
		. "\n c.`instructor_2_id`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x USING (`course_id`)"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. "\n LEFT JOIN #__users u ON (c.`instructor_id` = u.`id`)"
		. "\n LEFT JOIN #__pay p ON (x.`xref_id` = p.`xref_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY c.`course_id`"
		. "\n HAVING (SUM(p.`amount`)-IF(c.instructor_2_id>0,360,200)) < 0"
		;
		$database->setQuery( $query );
		$courses = $database->loadResultArray();
		echo $database->_errorMsg;
		$this->total = count($courses);
		unset($courses);
	}


	/**
	 * Loads losing class report for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param string $mode
	 * @param object $pageNav
	 * @return boolean
	 */
	function load( $mode = 'data', $pageNav = array() ) {
		global $database;

		$where = array();
		
		$where[] = "c.`status_id` <> " . A25_Record_Course::statusId_Cancelled;

		if ( $this->filter->from ) {
			$where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->filter->lid ) {
			$where[] = "c.`location_id`='" . (int) $this->filter->lid . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		switch ( $mode ) {
			case 'export':
				$sql = "SELECT c.`course_id` AS `Course ID`,"
				. "\n c.`instructor_id` AS `Instructor ID`,"
				. "\n c.`instructor_2_id` AS `Instructor 2 ID`,"
				. "\n l.`location_name` AS `Location Name`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_DATE_SHORT . "') AS `Course Date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_TIME_SHORT . "') AS `Course Time`,"
				. "\n u.`name` AS `Instructor Name`,"
				. "\n SUM(IF(x.`status_id`=1,1,0)) AS `Number Registered`,"
				. "\n SUM(IF(x.`status_id`=2,1,0)) AS `Number Student`,"
				. "\n SUM(IF(x.`status_id`=3,1,0)) AS `Number Completed`,"
				. "\n SUM(IF(x.`status_id`=4,1,0)) AS `Number Cancelled`,"
				. "\n SUM(IF(x.`status_id`=5,1,0)) AS `Number No Show`,"
				. "\n SUM(IF(x.`status_id`=6,1,0)) AS `Number Unknown`,"
				. "\n SUM(IF(x.`status_id`=7,1,0)) AS `Number Pending`,"
				. "\n SUM(IF(x.`court_id`>0,1,0)) AS `Number Court Ordered`,"
				. "\n SUM(IF(x.`court_id`=0 OR x.`court_id` IS NULL,1,0)) AS `Number Voluntary`,"
				. "\n CONCAT('-$',-(SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200))) AS `Gross Profit`"
				;
				break;
			case 'data':
			default:
				$sql = "SELECT c.`course_id`,"
				. "\n c.`instructor_id`,"
				. "\n c.`instructor_2_id`,"
				. "\n l.`location_id`,"
				. "\n l.`location_name`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_DATE_SHORT . "') AS `course_date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'" . _MYSQL_TIME_SHORT . "') AS `course_time`,"
				. "\n u.`name` AS `instructor_name`,"
				. "\n SUM(IF(x.`status_id`=1,1,0)) AS `num_R`,"
				. "\n SUM(IF(x.`status_id`=2,1,0)) AS `num_S`,"
				. "\n SUM(IF(x.`status_id`=3,1,0)) AS `num_C`,"
				. "\n SUM(IF(x.`status_id`=4,1,0)) AS `num_X`,"
				. "\n SUM(IF(x.`status_id`=5,1,0)) AS `num_N`,"
				. "\n SUM(IF(x.`status_id`=6,1,0)) AS `num_U`,"
				. "\n SUM(IF(x.`status_id`=7,1,0)) AS `num_P`,"
				. "\n SUM(IF(x.`court_id`>0,1,0)) AS `num_court_ordered`,"
				. "\n SUM(IF(x.`court_id`=0 OR x.`court_id` IS NULL,1,0)) AS `num_voluntary`,"
				. "\n CONCAT('-$',-(SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200))) AS `gross_revenue`"
				;
				break;
		}

		$sql .= "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x USING (`course_id`)"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)"
		. "\n LEFT JOIN #__users u ON (c.`instructor_id` = u.`id`)"
		. "\n LEFT JOIN #__pay p ON (x.`xref_id` = p.`xref_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY c.`course_id`"
		. "\n HAVING (SUM(p.`amount`)-IF(c.`instructor_2_id`>0,360,200)) < 0"
		. "\n ORDER BY c.`course_start_date`"
		. ( $mode == 'data' ? "\n LIMIT $pageNav->limitstart, $pageNav->limit" : "" )
		;
		$database->setQuery( $sql );

		switch ( $mode ) {
			case 'export':
				$this->data = $database->loadResultList();
				break;
			case 'data':
			default:
				$this->data = $database->loadObjectList();
				break;
		}

		echo $database->_errorMsg;
		//echo str_replace('#_','jos',$sql);

		return true;
	}
}


/**
 * Marketing stats class
 * @author Christiaan van Woudenberg
 * @version September 19, 2006
 *
 * @return void
 */
class marketingStats extends stats {

	/**
	 * Calculates marketing statistics for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @return boolean
	 */
	function summary() {
		global $database;
		$s = new stdClass();

		$where = array();

		if ( $this->filter->from ) {
			$where[] = "c.`course_start_date`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "c.`course_start_date`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		if ( $this->filter->lid ) {
			$where[] = "c.`location_id`='" . (int) $this->filter->lid . "'";
		}

		if ( $this->locs[0] != 'all' ) {
			$where[] = "c.`location_id` IN (" . implode(',',$this->locs) . ")";
		}

		// fetch age statistics
		$sql = A25_StatsQueryBuilder::age($where);

		$database->setQuery($sql);
		$age = $database->loadObjectList('age');
		echo $database->_errorMsg;

		$total = 0;
		foreach ($age as $a) {
			$total += $a->count;
		}
		$s->age->data = $age;
		$s->age->total = $total;

		$reason = $this->fetchReasonForEnrollmentStats($database, $where, true);

		$total = 0;
		$total_revenue = 0;
		foreach ($reason as $r) {
			$total += $r->count;
			$total_revenue += $r->gross_revenue;
		}
		$s->reason->data = $reason;
		$s->reason->total = $total;
		$s->reason->total_revenue = $total_revenue;

		// Fetch hear_about statistics
		$sql = "SELECT x.`hear_about_id`,h.`hear_about_name`, COUNT(*) AS `count`,"
		. "\n (SUM(p.`amount`)) AS `gross_revenue`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x ON (c.`course_id` = x.`course_id`)"
		. "\n LEFT JOIN #__pay p ON (x.`xref_id` = p.`xref_id`)"
		. "\n LEFT JOIN #__hear_about_type h ON (x.`hear_about_id` = h.`hear_about_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY x.`hear_about_id`"
		. "\n ORDER BY h.`hear_about_id`"
		;

		$database->setQuery($sql);
		$hear_about = $database->loadObjectList();
		echo $database->_errorMsg;

		$total = 0;
		$total_revenue = 0;
		foreach ($hear_about as $h) {
			$total += $h->count;
			$total_revenue += $h->gross_revenue;
		}
		$s->hear_about->data = $hear_about;
		$s->hear_about->total = $total;
		$s->hear_about->total_revenue = $total_revenue;

		// fetch gender statistics
		$sql = A25_StatsQueryBuilder::gender($where);

		$database->setQuery($sql);
		$gender = $database->loadObjectList('gender');
		echo $database->_errorMsg;

		$total = 0;
		foreach ($gender as $g) {
			$total += $g->count;
		}
		$s->gender->data = $gender;
		$s->gender->total = $total;

		// fetch court statistics
		$where[] = "co.`court_id`>0";
		$sql = "SELECT co.`court_id`,co.`court_name`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref x USING (`course_id`)"
		. "\n LEFT JOIN #__court co ON (x.`court_id` = co.`court_id` AND co.`court_id` IS NOT NULL)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY x.`court_id`"
		. "\n ORDER BY co.`court_name`"
		;

		$database->setQuery($sql);
		$court = $database->loadObjectList();
		echo $database->_errorMsg;

		$total = 0;
		foreach ($court as $c) {
			$total += $c->count;
		}
		$s->court->data = $court;
		$s->court->total = $total;

		$this->statistics = $s;
		return true;
	}
}


/**
 * Payment stats class
 * @author Christiaan van Woudenberg
 * @version September 10, 2006
 *
 * @return void
 */
class paymentStats extends stats {

	public $total_amount;

	/**
	 * Loads payment report for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @return boolean
	 */
	function count() {
		global $database;

		$where = array();


		if ( $this->filter->from ) {
			$where[] = "p.`created`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "p.`created`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		// get the total number of records
		$query = "SELECT COUNT(*) AS count,"
		. "\n SUM(p.`amount`) AS total_amount"
		. "\n FROM #__student_course_xref x"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__order o ON (o.`xref_id` = x.`xref_id`)"
		. "\n LEFT JOIN #__pay p ON (p.`xref_id` = x.`xref_id`)"
		. "\n LEFT JOIN #__pay_type pt ON (p.`pay_type_id` = pt.`pay_type_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		;
		$database->setQuery( $query );
		$results = $database->loadObjectList();
		$this->total = $results[0]->count;
		$this->total_amount = $results[0]->total_amount;
		//echo '<pre>' . str_replace('#_','jos',$query) . '</pre>';
	}


	/**
	 * Loads payment report for the current filter set.
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param string $mode
	 * @param object $pageNav
	 * @return boolean
	 */
	function load( $mode = 'data', $pageNav = array() ) {
		global $database;

		$where = array();

		if ( $this->filter->from ) {
			$where[] = "p.`created`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "p.`created`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}

		switch ( $mode ) {
			case 'export':
				$sql = "SELECT DATE_FORMAT(p.`created`,'%Y-%m-%d') AS `Payment Date`,"
				. "\n p.`pay_id` AS `Payment ID`,"
				. "\n s.`student_id` AS `Student ID`,"
				. "\n s.`last_name` AS `Last Name`,"
				. "\n s.`first_name` AS `First Name`,"
				. "\n c.`course_id` AS `Course ID`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'%Y-%m-%d') AS `Course Date`,"
				. "\n pt.`pay_type_name` AS `Payment Method`,"
				. "\n p.`amount` AS `Amount`,"
				. "\n p.`paid_by_name` AS `Paid By`,"
				. "\n p.`cc_trans_id` AS `CC Transaction ID`,"
				. "\n p.`check_number` AS `Check Number`,"
				. "\n p.`notes` AS `Notes`,"
				. "\n s.`email` AS `Email`"
				;
				break;
			case 'data':
			default:
				$sql = "SELECT DATE_FORMAT(p.`created`,'%Y-%m-%d') AS `payment_date`,"
				. "\n p.`pay_id`,"
				. "\n s.`student_id`,"
				. "\n s.`last_name`,"
				. "\n s.`first_name`,"
				. "\n c.`course_id`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'%Y-%m-%d') AS `course_date`,"
				. "\n pt.`pay_type_name`,"
				. "\n p.`amount`,"
				. "\n p.`paid_by_name`,"
				. "\n p.`cc_trans_id`,"
				. "\n p.`check_number`,"
				. "\n p.`notes`,"
				. "\n s.`email`"
				;
				break;
		}

		$sql .= "\n FROM #__student_course_xref x"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__order o ON (o.`xref_id` = x.`xref_id`)"
		. "\n LEFT JOIN #__pay p ON (p.`xref_id` = x.`xref_id`)"
		. "\n LEFT JOIN #__pay_type pt ON (p.`pay_type_id` = pt.`pay_type_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY p.`created`"
		. ( $mode == 'data' ? "\n LIMIT $pageNav->limitstart, $pageNav->limit" : "" )
		;
		$database->setQuery( $sql );

		switch ( $mode ) {
			case 'export':
				$this->data = $database->loadResultList();
				break;
			case 'data':
			default:
				$this->data = $database->loadObjectList();
				break;
		}

		echo $database->_errorMsg;
		//echo '<pre>' . str_replace('#_','jos',$sql) . '</pre>';

		return true;
	}
}


/**
 * Credit Type stats class
 * @author Garey Hoffman
 * @author Thomas Albright
 * @version NEW
 *
 * @return void
 */
class creditTypeStats extends stats {

	/**
	 * Loads payment report for the current filter set.
	 * @author Garey Hoffman
	 * @author Thomas Albright
	 * @version NEW
	 * @since September 10, 2006
	 *
	 * @return boolean
	 */
	function count() {
		global $database;

		$where = $this->createWhereArray();

		// get the total number of records
		$query = "SELECT COUNT(*), SUM(p.`amount`) AS `summation`";
		$query .= $this->createFromClause();
		$query .= ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" );
		$database->setQuery( $query );
		$this->total = $database->loadResult();
		$results = $database->loadObjectList();
		$this->summation = $results[0]->summation;
		//echo '<pre>' . str_replace('#_','jos',$query) . '</pre>';
	}


	/**
	 * Loads payment report for the current filter set.
	 * @author Garey Hoffman
	 * @author Thomas Albright
	 * @version NEW
	 * @since September 10, 2006
	 *
	 * @param string $mode
	 * @param object $pageNav
	 * @return boolean
	 */
	function load( $mode = 'data', $pageNav = array() ) {
		global $database;

		$where = $this->createWhereArray();

		switch ( $mode ) {
			case 'export':
				$sql = "SELECT DATE_FORMAT(c.`created`,'%Y-%m-%d') AS `Payment Date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'%Y-%m-%d') AS `Course Start Date`,"
				. "\n p.`pay_id` AS `Payment ID`,"
				. "\n s.`student_id` AS `Student ID`,"
				. "\n s.`last_name` AS `Last Name`,"
				. "\n s.`first_name` AS `First Name`,"
				. "\n c.`course_id` AS `Course ID`,"
				. "\n ct.`credit_type_name` AS `Credit Type`,"
				. "\n p.`amount` AS `Amount`,"
				. "\n l.`location_name` AS `Location Name`";
				;
				break;
			case 'data':
			default:
				$sql = "SELECT DATE_FORMAT(p.`created`,'%Y-%m-%d') AS `payment_date`,"
				. "\n DATE_FORMAT(c.`course_start_date`,'%Y-%m-%d') AS `course_start_date`,"
				. "\n p.`pay_id`,"
				. "\n s.`student_id`,"
				. "\n s.`last_name`,"
				. "\n s.`first_name`,"
				. "\n c.`course_id`,"
				. "\n ct.`credit_type_name`,"
				. "\n p.`amount`,"
				. "\n l.`location_name`";
				;
				break;
		}

		$sql .= $this->createFromClause();
		$sql .= ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY p.`created`"
		. ( $mode == 'data' ? "\n LIMIT $pageNav->limitstart, $pageNav->limit" : "" )
		;
		//echo $sql;
		$database->setQuery( $sql );

		switch ( $mode ) {
			case 'export':
				$this->data = $database->loadResultList();
				break;
			case 'data':
			default:
				$this->data = $database->loadObjectList();
				break;
		}

		echo $database->_errorMsg;
		//echo '<pre>' . str_replace('#_','jos',$sql) . '</pre>';

		return true;
	}

	/**
	 * Helper function used by $this->count() and $this->load().  It adds the 
	 * FROM clause.
	 *
	 * @author Thomas Albright
	 * @return string
	 * @version NEW
	 * @since NEW
	 */
	function createFromClause() {
		$sql = "\n FROM #__credit_type ct"
		. "\n LEFT JOIN #__credits cs USING (`credit_type_id`)"
		. "\n LEFT JOIN #__student_course_xref x USING (`xref_id`)"
		. "\n LEFT JOIN #__student s ON (s.`student_id` = x.`student_id`)"
		. "\n LEFT JOIN #__course c ON (x.`course_id` = c.`course_id`)"
		. "\n LEFT JOIN #__location l ON (l.`location_id` = x.`location_id`)"
		. "\n LEFT JOIN #__pay p USING (`pay_id`)"
		;
		return $sql;
	}

	/**
	 * Helper function used by $this->count() and $this->load().  It adds the 
	 * WHERE clauses based upon the forms for Credit Type and dates.
	 *
	 * @author Thomas Albright
	 * @return array of SQL 'WHERE' statements
	 * @version NEW
	 * @since NEW
	 */
	function createWhereArray () {
		$where = array();

		// Added 2008-01-02 (Thomas Albright):

		if ( $this->filter->credit_type ) {
			$where[] = "ct.`credit_type_id` = " . $this->filter->credit_type;
		}

		if ( $this->filter->from ) {
			$where[] = "p.`created`>='" . date('Y-m-d H:i:s',$this->filter->from) . "'";
		}

		if ( $this->filter->to ) {
			$where[] = "p.`created`<='" . date('Y-m-d H:i:s',$this->filter->to) . "'";
		}
		return $where;
	}
}
