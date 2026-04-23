<?php

class A25_RowFormatter4FeeReports extends A25_StrictObject
{
  protected $orderItem;
  
  public function __construct(A25_DoctrineRecord $orderItem) {
    $this->orderItem = $orderItem;
  }
  
  protected function studentLink()
  {
    return A25_RecordLinks::studentLink($this->orderItem->studentId());
  }
	
  protected function enrollLink()
  {
    return A25_RecordLinks::enrollLink($this->orderItem->enrollmentId());
  }
  
  protected function courseLink()
  {
    return A25_RecordLinks::courseLink($this->orderItem->courseId());
  }
  
  protected function payStatus()
  {
    if ($this->orderItem->relatedIsDefined('Order')) {
      // We had to, at least temporarily, check whether or not the Order is paid
      // rather than checking based on payment date or using OrderItem->isPaid()
      // because we did not start marking line item date_paid until 2010.  If we
      // ever do go back and fix the pre-2010 data so that they have payment
      // dates, then we should change this to $orderItem->isPaid().
			if ($this->orderItem->Order->isPaid())
				return 'Paid';
			else
				return 'Not paid'; 
    }
  }
  
  protected function courseDate()
  {
    return A25_Functions::stringToDate($this->orderItem->courseDatetime());
  }
}