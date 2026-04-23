<?php

namespace Acre\A25\Query;

use AppDevl\QueryStrategy\StrategyInterface;

/**
 * While it seems wasteful to pass $query around as a parameter, it is necessary
 * because this class can be used to build queries both as strings as well as
 * objects such as Doctrine_Query. Since $query can be a string, and since it
 * could be modified elsewhere between steps, we would have to enforce passing
 * by reference everywhere in order for it to be a class property, and that
 * could cause confusion.  This extra parameter is less costly than confusion
 * over pass-by-reference.
 */
class CourseJoiner
{
    /**
     * @var StrategyInterface
     */
    private $strategy;

    private $joined_course = false;
    private $joined_enrollments = false;
    private $joined_orders = false;
    private $joined_order_items = false;

    public function __construct(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function course($query)
    {
        if ($this->joined_course) {
            return $query;
        }

        $query = $this->strategy->from($query, 'jos_course c');
        $this->joined_course = true;

        return $query;
    }

    public function enrollments($query)
    {
        if ($this->joined_enrollments) {
            return $query;
        }

        $new_query = $this->course($query);
        $new_query = $this->strategy->leftJoin($new_query, 'c.Enrollments');
        $this->joined_enrollments = true;

        return $new_query;
    }

    public function orders($query)
    {
        if ($this->joined_orders) {
            return $query;
        }

        $new_query = $this->enrollments($query);
        $new_query = $this->strategy->leftJoin($new_query, 'e.Order');
        $this->joined_orders = true;

        return $new_query;
    }

    public function orderItems($query)
    {
        if ($this->joined_order_items) {
            return $query;
        }

        $new_query = $this->orders($query);
        $new_query = $this->strategy->leftJoin($new_query, 'o.OrderItems');
        $this->joined_order_items = true;

        return $new_query;
    }
    
    public static function doctrineToSqlTranslations()
    {
        return array('c.Enrollments' =>
            'jos_student_course_xref e ON c.course_id = e.course_id',
            'e.Order' => 'jos_order o ON e.xref_id = o.xref_id',
            'o.OrderItems' => 'jos_order_item i ON o.order_id = i.order_id');
    }
}
