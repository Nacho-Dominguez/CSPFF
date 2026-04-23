<?php

class A25_Record_OrderItem_Donation extends A25_Record_OrderItem
{
  protected function accrualDateForActiveItem()
  {
    return null;
  }
  
  public function markPaid()
  {
    parent::markPaid();
    $this->fireMarkPaidForDonation();
  }
  
  private function fireMarkPaidForDonation()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_MarkPaidForDonation)
        $listener->appendMarkPaidForDonation($this);
    }
  }
}
