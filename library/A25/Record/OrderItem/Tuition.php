<?php

class A25_Record_OrderItem_Tuition extends A25_Record_OrderItem
{
  protected function accrualDateForActiveItem()
  {
    $rules = A25_DI::Factory()->BusinessRules();
    if ($this->wasAttended())
      return $rules->tuitionAccrualDate($this);
    else
      return null;
  }
}
