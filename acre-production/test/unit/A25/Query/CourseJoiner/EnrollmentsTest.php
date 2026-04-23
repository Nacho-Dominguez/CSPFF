<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class EnrollmentsTest
    extends \test_Framework_UnitTestCase
{
    public function testWhenEnrollmentsNotPresent_JoinsEnrollments()
    {
        $strategy = new MysqlStringStrategy(CourseJoiner::doctrineToSqlTranslations());
        $joiner = new CourseJoiner($strategy);
        $query = $joiner->enrollments('SELECT *');
        $this->assertEquals(
            "SELECT * FROM jos_course c\n"
            . "LEFT JOIN jos_student_course_xref e ON c.course_id = e.course_id",
            $query);
    }
    public function testWhenEnrollmentsAlreadyJoined_DoesNotJoinAgain()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new CourseJoiner($strategy);
        $before = $joiner->enrollments('SELECT *');
        $after = $joiner->enrollments($before);
        $this->assertEquals($before, $after);
    }
}
