<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\MysqlStringStrategy;

class SelectTest
    extends \test_Framework_UnitTestCase
{
    public function testAppendsSelect()
    {
        $strategy = new MysqlStringStrategy();
        $joiner = new \Acre\A25\Query\CourseJoiner($strategy);
        $query = new CourseGrossRevenueQuery($joiner, $strategy);
        $output = $query->select('');
        $this->assertEquals('SELECT SUM(IF(e.status_id NOT IN (4,9,5),IF('
            . 'i.type_id IN (1),i.unit_price,0),0)) as gross_revenue', $output);
    }
}
