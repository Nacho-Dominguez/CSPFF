<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class OrdersTest
    extends \test_Framework_UnitTestCase
{
    public function testWhenOrdersNotPresent_JoinsOrders()
    {
        $strategy = new MysqlStringStrategy(CourseJoiner::doctrineToSqlTranslations());
        $joiner = new CourseJoiner($strategy);
        $query = $joiner->orders('SELECT *');
        $this->assertEquals(
            "SELECT * FROM jos_course c\n"
            . "LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id"
            . "\nLEFT JOIN jos_order o ON e.xref_id = o.xref_id",
            $query);
    }
    public function testWhenOrdersAlreadyJoined_DoesNotJoinAgain()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new CourseJoiner($strategy);
        $before = $joiner->orders('SELECT *');
        $after = $joiner->orders($before);
        $this->assertEquals($before, $after);
    }
}
