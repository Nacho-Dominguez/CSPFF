<?php

class util_checkRecords_StudentCalcs extends util_checkRecords_RecordChecker
{
  public function selectQuery()
  {
    return Doctrine_Query::create()
         ->from('A25_Record_Student s')
         ->innerJoin('s.Enrollments e')
         ->innerJoin('e.Order o')
         ->innerJoin('o.OrderItems i')
         ->leftJoin('o.Payments p');
  }
}
