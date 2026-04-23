<?php

class util_checkRecords_PartSplitter
{
  const LIMIT = 1000;
  
  private $class_name;
  private $total_count;
  private $earliest_record;
  
  public function __construct($class_name, $total_count,
      $number_of_most_recent_records_to_update)
  {
    $this->class_name = $class_name;
    $this->total_count = $total_count;
    $this->earliest_record = self::earliestRecordToUpdate(
        $number_of_most_recent_records_to_update, $total_count);
  }
  
  protected static function earliestRecordToUpdate(
      $number_of_most_recent_records_to_update, $total_count)
  {
    if ($number_of_most_recent_records_to_update)
      $earliest_record = $total_count - $number_of_most_recent_records_to_update;

    if ($earliest_record < 0 || empty($earliest_record))
      $earliest_record = 0;
    
    return $earliest_record;
  }
  
  public function run()
  {
    $this->execute($this->total_count - self::LIMIT);
  }
  
  protected function execute($offset)
  { 
    if ($offset <= $this->earliest_record)
      $offset = $this->earliest_record;
    else
      $this->execute($offset - self::LIMIT);

    $this->runPart($offset);
  }

  protected function runPart($offset)
  {
    $script = dirname(__FILE__) . '/run_part.php';

    echo `php $script $this->class_name $offset`;

    echo ".";
  }
}
