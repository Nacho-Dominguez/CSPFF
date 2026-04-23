<?php

class test_unit_A25_View_Student_Account_CertificateMessageTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function hasLateFeeAndBeforeCourse()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = new A25_Record_Enroll();
    $enroll->Course = $course;
    $enroll->Order = $order;
    
    $expected = '<p><b>Proof of completion:</b><br/>Since you enrolled within 24 hours of the class, it is possible that the instructor will not have your Certificate of Completion pre-printed. If the instructor does not have your certificate at the class, please call us at (720) 269-4046 during normal business hours to arrange for us to mail it to you, or for you to come by the office to pick it up.</p>';
    
    $view = new CertificateMessageTest_AccountView($enroll);
    $this->assertEquals($expected, $view->certificateMessage());
  }
  
  /**
   * @test
   */
  public function hasNoLateFee()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $order = new A25_Record_Order();
    
    $enroll = new A25_Record_Enroll();
    $enroll->Course = $course;
    $enroll->Order = $order;
    
    $view = new CertificateMessageTest_AccountView($enroll);
    $this->assertEquals($this->expectedWithoutLateFee(),
        $view->certificateMessage());
  }
  
  /**
   * @test
   */
  public function afterCourse()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('-12 hours');
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = new A25_Record_Enroll();
    $enroll->Course = $course;
    $enroll->Order = $order;
    
    $view = new CertificateMessageTest_AccountView($enroll);
    $this->assertEquals($this->expectedWithoutLateFee(),
        $view->certificateMessage());
  }
  
  private function expectedWithoutLateFee()
  {
    return '<p><b>Proof of completion:</b><br/>Students will receive a certificate of completion from the
      instructor immediately following the successful completion of the course.</p>';
  }
}

class CertificateMessageTest_AccountView
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function certificateMessage()
  {
    return parent::certificateMessage();
  }
}
