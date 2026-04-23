<?php

class util_checkRecords_DatePaidWereSet extends util_checkRecords_RecordChecker {
  public function selectQuery()
  {
    return Doctrine_Query::create()
         ->from('A25_Record_Student s')
         ->innerJoin('s.Enrollments e')
         ->innerJoin('e.Order o')
         ->innerJoin('o.OrderItems i');
  }
  public function execute($record)
  {
    $record->markAppropriateOrdersAndLineItemsAsPaid();

    foreach($record->getFees() as $fee)
      if($fee->isModified())
        echo("Fee $fee->item_id was modified.\n");
  }
}
