<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class GroupByTest
    extends \test_Framework_UnitTestCase
{
    public function testAppendsGroupBy()
    {
        $strategy = new MysqlStringStrategy(CourseJoiner::doctrineToSqlTranslations());
        $joiner = new \Acre\A25\Query\CourseJoiner($strategy);
        $query = new CourseGrossRevenueQuery($joiner, $strategy);
        $output = $query->groupBy(
            "SELECT SUM(i.unit_price) as gross_revenue FROM jos_course c\n"
            . "LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id"
            . "\nLEFT JOIN jos_order o ON e.xref_id = o.xref_id"
            . "\nLEFT JOIN jos_order_item i ON o.order_id = i.order_id");
        $this->assertEquals(
            "SELECT SUM(i.unit_price) as gross_revenue FROM jos_course c\n"
            . "LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id"
            . "\nLEFT JOIN jos_order o ON e.xref_id = o.xref_id"
            . "\nLEFT JOIN jos_order_item i ON o.order_id = i.order_id"
            . " GROUP BY c.course_id",
            $output);
    }
}
