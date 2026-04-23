<?php

namespace Acre\A25\Query;

use \AppDevl\QueryStrategy\StrategyInterface;

class CourseGrossRevenueQuery implements QueryInterface
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    /**
     * @var CourseJoiner
     */
    private $joiner;

    public function __construct(CourseJoiner $joiner, StrategyInterface $strategy)
    {
        $this->joiner = $joiner;
        $this->strategy = $strategy;
    }

    public function select($query)
    {
        return $this->strategy->select($query, 'SUM(IF('
            . \A25_Record_Enroll::elligibleForCourseRevenue('e')
            . ',IF(' . \A25_Record_OrderItem::elligibleForCourseRevenue('i')
            . ',i.unit_price,0),0)) as gross_revenue');
    }

    public function from($query)
    {
        return $this->joiner->orderItems($query);
    }

    public function groupBy($query)
    {
        return $this->strategy->groupBy($query, 'c.course_id');
    }
}