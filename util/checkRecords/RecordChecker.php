<?php

abstract class util_checkRecords_RecordChecker
{
  abstract public function selectQuery();
  
  public function execute($record)
  {
    $record->updateCalculatedValues();
  }
}