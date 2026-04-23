<?php

class A25_Record_OrderItem_ReplaceCertFee extends A25_Record_OrderItem
{
  protected function accrualDateForActiveItem()
  {
    return A25_Functions::stringToDate($this->created);
  }
}
