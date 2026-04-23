<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once(dirname(__FILE__) . "/../../../autoload.php");

/**
 * Description of StatsQueryBuilder
 *
 * @author remote
 */
class test_unit_A25_StatsQueryBuilder extends
		test_Framework_UnitTestCase {
    public function test_reasonEnrolled_base()
	{
		$expected = "SELECT e.`reason_id`, r.`reason_name`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref e USING (`course_id`)"
		. "\n LEFT JOIN #__reason_type r ON (e.`reason_id` = r.`reason_id`)"
		. "\n GROUP BY e.`reason_id`"
		. "\n ORDER BY e.`reason_id`";
		$actual = A25_StatsQueryBuilder::reasonEnrolled(array());
		$this->assertEquals($expected,$actual);
    }
    public function test_reasonEnrolled_wRevenue()
	{
		$expected = "SELECT e.`reason_id`, r.`reason_name`, "
		. "(SUM(p.`amount`)) AS `gross_revenue`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref e USING (`course_id`)"
		. "\n LEFT JOIN #__pay p ON (e.`xref_id` = p.`xref_id`)"
		. "\n LEFT JOIN #__reason_type r ON (e.`reason_id` = r.`reason_id`)"
		. "\n GROUP BY e.`reason_id`"
		. "\n ORDER BY e.`reason_id`";
		$actual = A25_StatsQueryBuilder::reasonEnrolled(array(),true);
		$this->assertEquals($expected,$actual);
    }
	public function test_gender()
	{
		$expected = "SELECT s.`gender`, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref e USING (`course_id`)"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n GROUP BY s.`gender`"
		. "\n ORDER BY s.`gender`";
		$actual = A25_StatsQueryBuilder::gender(array());
		$this->assertEquals($expected,$actual);
    }
	public function test_age()
	{
		$expected = "SELECT (DATE_FORMAT(c.`course_start_date`,'%Y') - DATE_FORMAT(s.`date_of_birth`,'%Y') - (DATE_FORMAT(c.`course_start_date`,'00-%m-%d') < DATE_FORMAT(s.`date_of_birth`,'00-%m-%d'))) AS age, COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref e USING (`course_id`)"
		. "\n LEFT JOIN #__student s USING (`student_id`)"
		. "\n GROUP BY age"
		. "\n ORDER BY age";
		$actual = A25_StatsQueryBuilder::age(array());
		$this->assertEquals($expected,$actual);
    }
}
