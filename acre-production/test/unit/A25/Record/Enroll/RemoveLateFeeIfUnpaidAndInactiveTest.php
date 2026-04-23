<?php

class test_unit_A25_Record_Enroll_RemoveLateFeeIfUnpaidAndInactiveTest extends
		test_Framework_UnitTestCase
{
  /**
   * @todo-jon-low-small - remove duplication
   */
  /**
   * @test
   */
  public function doesNotRemoveIfActive()
  {
    $enroll = $this->enrollmentWithStatusOf(A25_Record_Enroll::statusId_registered);
    $order = new A25_Record_Order();
    $fee = $this->getMock('A25_Record_OrderItem', array('delete'));
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    $enroll->Order = $order;
    
    $enroll->expects($this->never())->method('getLineItemOfType');
    $enroll->removeLateFeeIfUnpaidAndInactive();
  }
  
  /**
   * @test
   */
  public function doesNotRemoveIfPaid()
  {
    $enroll = $this->enrollmentWithStatusOf(A25_Record_Enroll::statusId_canceled);
    $order = new A25_Record_Order();
    $fee = $this->getMock('A25_Record_OrderItem', array('delete', 'isPaid'));
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->date_paid = A25_Functions::formattedDateTime('Yesterday');
    $fee->Order = $order;
    $enroll->Order = $order;
    
    $enroll->expects($this->once())->method('getLineItemOfType')
        ->with(A25_Record_OrderItemType::typeId_LateFee)
        ->will($this->returnValue($fee));
    $fee->expects($this->once())->method('isPaid')
        ->will($this->returnValue(true));
    
    $fee->expects($this->never())->method('delete');
    $enroll->removeLateFeeIfUnpaidAndInactive();
  }
  
  /**
   * @test
   */
  public function removesIfUnpaidAndInactive()
  {
    $enroll = $this->enrollmentWithStatusOf(A25_Record_Enroll::statusId_canceled);
    $order = new A25_Record_Order();
    $fee = $this->getMock('A25_Record_OrderItem', array('delete', 'isPaid'));
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    $enroll->Order = $order;
    
    $enroll->expects($this->once())->method('getLineItemOfType')
        ->with(A25_Record_OrderItemType::typeId_LateFee)
        ->will($this->returnValue($fee));
    $fee->expects($this->once())->method('isPaid')
        ->will($this->returnValue(false));
    
    $fee->expects($this->once())->method('delete');
    $enroll->removeLateFeeIfUnpaidAndInactive();
  }
  
	/**
	 * @param int $status_id
	 * @return A25_Record_Enroll (actually, a mock of it)
	 */
	private function enrollmentWithStatusOf($status_id)
	{
		$enroll = $this->getMock('A25_Record_Enroll', array('checkAndStore',
        'getLineItemOfType'));
		$enroll->status_id = $status_id;
		
		return $enroll;
	}
}
