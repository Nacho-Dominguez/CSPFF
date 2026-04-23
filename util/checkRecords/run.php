<?php

/**
 * This script updates the calculated fields in jos_order_item.  By default, it
 * updates every single record.  However, if a number is passed as the first
 * argument, it will only update the last of that many records.
 * 
 * 
 * For example, if there are 180,000 records, running:
 * 
 * php run.php 10000
 * 
 * will update records 170,000-180,000. 
 */

require_once dirname(__FILE__) . '/../../autoload.php';


$record_checker_class = $argv[1];
$number_of_most_recent_records_to_update = $argv[2];

$checker = new $record_checker_class();
$total_count = $checker->selectQuery()->count();

if ($number_of_most_recent_records_to_update > 0)
  echo "Running on the most recent $number_of_most_recent_records_to_update out of a total of $total_count\n";
else
  echo "Running on all $total_count records\n";

$calculator = new util_checkRecords_PartSplitter($record_checker_class, $total_count,
    $number_of_most_recent_records_to_update);

$calculator->run();