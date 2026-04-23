<?php

class A25_RecordColumn
{
  private $record;
  private $fieldname;
  
  public function __construct($record, $fieldname) {
    $this->record = $record;
    $this->fieldname = $fieldname;
  }
  
  public function modifyDuringSet($value) {
    if ($this->isHashed())
      $value = $this->hash($value);
    
    return $value;
  }
  
  protected function isHashed() {
    if ($this->getColumnOption('hashed') == true)
      return true;
    
    return false;
  }
  
  private function getColumnOption($option) {
    $column = $this->getColumnDefinition();
    
    return $column[$option];
  }
  
  private function getColumnDefinition() {
    return $this->record->getTable()->getColumnDefinition($this->fieldname);
  }
  
  protected function hash($value) {
    if (!$value)
      return $value;
    
    return A25_DI::Hasher()->hash($this->saltPrefix(), $value);
  }
  
  /**
   * We assume that any DoctrineRecord with a field flagged 'hashed' also has
   * a 'salt_prefix' field.
   */
  protected function saltPrefix() {
    if (!$this->record->salt_prefix)
      $this->record->salt_prefix = A25_DI::Hasher()->generateSalt();

    return $this->record->salt_prefix;
  }
}
