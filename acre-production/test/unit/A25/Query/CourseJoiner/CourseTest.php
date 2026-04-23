<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class CourseTest
    extends \test_Framework_UnitTestCase
{
    public function testWhenCourseNotPresent_AppendsFromCourse()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new CourseJoiner($strategy);
        $query = $joiner->course('SELECT *');
        $this->assertEquals("SELECT * FROM jos_course c", $query);
    }
    public function testWhenCourseAlreadyJoined_DoesNotJoinAgain()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new CourseJoiner($strategy);
        $before = $joiner->course('SELECT *');
        $after = $joiner->course($before);
        $this->assertEquals($before, $after);
    }
}
