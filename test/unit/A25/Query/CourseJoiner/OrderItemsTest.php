<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class OrderItemsTest
    extends \test_Framework_UnitTestCase
{
    public function testWhenOrderItemsNotPresent_JoinsOrderItems()
    {
        $strategy = new MysqlStringStrategy(CourseJoiner::doctrineToSqlTranslations());
        $joiner = new CourseJoiner($strategy);
        $query = $joiner->orderItems('SELECT *');
        $this->assertEquals(
            "SELECT * FROM jos_course c\n"
            . "LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id"
            . "\nLEFT JOIN jos_order o ON e.xref_id = o.xref_id"
            . "\nLEFT JOIN jos_order_item i ON o.order_id = i.order_id",
            $query);
    }
    public function testWhenOrderItemsAlreadyJoined_DoesNotJoinAgain()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new CourseJoiner($strategy);
        $before = $joiner->orderItems('SELECT *');
        $after = $joiner->orderItems($before);
        $this->assertEquals($before, $after);
    }
}
