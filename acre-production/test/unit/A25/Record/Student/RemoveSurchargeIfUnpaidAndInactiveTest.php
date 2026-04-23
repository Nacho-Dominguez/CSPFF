<?php

class test_unit_A25_Record_Student_RemoveSurchargeIfUnpaidAndInactiveTest extends
		test_Framework_UnitTestCase
{
	/**
   * @test 
   */
  public function deletesSurchargeIfCancelingEnrollment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_canceled;
    
    $order = new A25_Record_Order();
    $order->Enrollment = $enroll;
    
    $fee = $this->getMock('A25_Record_OrderItem',array('delete'));
    $fee->expects($this->once())->method('delete');
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $order->OrderItems[] = $fee;
    
    $enroll->removeSurchargeIfUnpaidAndInactive();
  }
  
	/**
   * @test 
   */
  public function deletesSurchargeIfKickedOut()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_kickedOut;
    
    $order = new A25_Record_Order();
    $order->Enrollment = $enroll;
    
    $fee = $this->getMock('A25_Record_OrderItem',array('delete'));
    $fee->expects($this->once())->method('delete');
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $order->OrderItems[] = $fee;
    
    $enroll->removeSurchargeIfUnpaidAndInactive();
  }
  
  /**
   * @test 
   */
  public function deletesSurchargeIfNoShowEnrollment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_noShow;
    
    $order = new A25_Record_Order();
    $order->Enrollment = $enroll;
    
    $fee = $this->getMock('A25_Record_OrderItem',array('delete'));
    $fee->expects($this->once())->method('delete');
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $order->OrderItems[] = $fee;
    
    $enroll->removeSurchargeIfUnpaidAndInactive();
  }
  
  /**
   * @test 
   */
  public function doesNotDeleteSurchargeIfNotCancelingEnrollment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $order = new A25_Record_Order();
    $order->Enrollment = $enroll;
    
    $fee = $this->getMock('A25_Record_OrderItem',array('delete'));
    $fee->expects($this->never())->method('delete');
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $order->OrderItems[] = $fee;
    
    $enroll->removeSurchargeIfUnpaidAndInactive();
  }
 
  /**
   * @test 
   */
  public function doesNotDeleteSurchargeIfPaid()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_noShow;
    
    $order = new A25_Record_Order();
    $order->Enrollment = $enroll;
    
    $fee = $this->getMock('A25_Record_OrderItem',array('delete'));
    $fee->expects($this->never())->method('delete');
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $fee->date_paid = '2012-04-04';
    $order->OrderItems[] = $fee;
    
    $enroll->removeSurchargeIfUnpaidAndInactive();
  }
}