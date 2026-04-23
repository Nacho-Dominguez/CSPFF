<?php
require_once(dirname(__FILE__) . '/../../../../administrator/components/com_stats/stats.class.php');

class test_unit_Stats_LocationStats_CourseRevenueQueryTest
    extends \test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
    public function returnsExpectedQuery()
    {
        $filter = new A25_ReportFilter();
        $locs = array();
        $stats = new test_unit_Stats_LocationStats_CourseRevenueQueryTest_LocationStats($filter, $locs);
        $output = $stats->courseRevenueQuery();
        $this->assertEquals("SELECT c.`course_id`,
 l.`location_id`,
 l.`location_name`,
 cs.`status_name` AS `course_status`,
 DATE_FORMAT(c.`course_start_date`,'%b %d, %Y') AS `course_date`,
 DATE_FORMAT(c.`course_start_date`,'%h:%i %p') AS `course_time`,
 SUM(IF(e.status_id NOT IN (4,9,5),IF(i.type_id IN (1),1,0),0)) AS `num_students`,
 c.`course_capacity`, SUM(IF(e.status_id NOT IN (4,9,5),IF(i.type_id IN (1),i.unit_price,0),0)) as gross_revenue FROM jos_course c
LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id
LEFT JOIN jos_order o ON e.xref_id = o.xref_id
LEFT JOIN jos_order_item i ON o.order_id = i.order_id
 LEFT JOIN #__student s USING (`student_id`)
 LEFT JOIN #__location l ON (c.`location_id` = l.`location_id`)
 LEFT JOIN #__course_status cs ON (c.`status_id` = cs.`status_id`)
 LEFT JOIN #__pay p ON (e.`xref_id` = p.`xref_id`)
 WHERE c.`location_id` IN () GROUP BY c.course_id
 ORDER BY c.`course_start_date`",
            $output);
    }
}

class test_unit_Stats_LocationStats_CourseRevenueQueryTest_LocationStats
extends locationStats
{
  public function courseRevenueQuery() {
      return parent::courseRevenueQuery();
  }
}