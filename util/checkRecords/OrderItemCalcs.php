<?php

class util_checkRecords_OrderItemCalcs extends util_checkRecords_RecordChecker {
  public function selectQuery()
  {
    return Doctrine_Query::create()
         ->from('A25_Record_OrderItem i')
         ->innerJoin('i.Order o')
         ->innerJoin('o.Enrollment e')
         ->innerJoin('e.Course c');
  }
}
