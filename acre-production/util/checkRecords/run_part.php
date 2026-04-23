<?php

require_once dirname(__FILE__) . '/../../autoload.php';

$limit = util_checkRecords_PartSplitter::LIMIT;
$record_checker_class = $argv[1];
$offset = $argv[2];

$checker = new $record_checker_class();
$query = $checker->selectQuery();

$records = $query->limit($limit)->offset($offset)->execute();

foreach ($records as $record) {
  $checker->execute($record);
}

$records->save();

exit(0);
