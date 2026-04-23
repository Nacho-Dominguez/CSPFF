<?php

class A25_Record_OrderItem_ExpiredPayment extends A25_Record_OrderItem
{
  protected function accrualDateForActiveItem()
  {
    return A25_Functions::stringToDate($this->created);
  }
}
