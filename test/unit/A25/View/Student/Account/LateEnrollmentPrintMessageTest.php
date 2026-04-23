<?php

/**
 * @todo-jon-low-small - refactor tests so that we just have an array that defines
 * every parameter for the scenario, similar to UpdateStatusAfterPaymentTest
 */
class test_unit_A25_View_Student_Account_LateEnrollmentPrintMessageTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function hasLateFeeAndCourseIsNotPastAndStudentHasPaid()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = new A25_Record_Student();
    $student->student_id=9;
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $expected = '<p style="font-style: italic">Since you enrolled within 24 hours of
      the class, please print out this page and bring it with you to the class,
      in case the instructor printed out the roster before you enrolled.  If you
      do not have access to a printer, please write down your student ID and this
      course ID on a sheet of paper and bring it to class:</p>
      <p style="margin-left: 24px;">Student ID: ' . $student->student_id . '<br/>Course ID: '
      . $enroll->course_id . '</p>';
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertEquals($expected, $view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function hasNoLateFee()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = new A25_Record_Student();
    
    $order = new A25_Record_Order();
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertEquals('', $view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function courseIsPast()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('-1 hour');
    
    $student = new A25_Record_Student();
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(true));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertEquals('', $view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function studentHasNotPaid()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = $this->getMock('A25_Record_Student', array('getAccountBalance'));
    $student->expects($this->once())->method('getAccountBalance')
        ->will($this->returnValue(79));
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertEquals('', $view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function whenEnrollmentIsCanceled_returnsNull()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = new A25_Record_Student();
    $student->student_id=9;
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_canceled;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertNull($view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function whenEnrollmentIsKickedOut_returnsNull()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = new A25_Record_Student();
    $student->student_id=9;
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_kickedOut;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertNull($view->lateEnrollmentPrintMessage());
  }
  /**
   * @test
   */
  public function whenEnrollmentHasBeenAttended_returnsNull()
  {
    $course = new A25_Record_Course();
    $course->start_time = A25_Functions::formattedDateTime('12 hours');
    
    $student = new A25_Record_Student();
    $student->student_id=9;
    
    $order = new A25_Record_Order();
    
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->Order = $order;
    
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(true));
    $enroll->Course = $course;
    $enroll->Student = $student;
    $enroll->Order = $order;
    
    $enroll->status_id = A25_Record_Enroll::statusId_completed;
    
    $view = new LateEnrollmentPrintMessageTest_AccountView($student, $enroll);
    $this->assertNull($view->lateEnrollmentPrintMessage());
  }
}

class LateEnrollmentPrintMessageTest_AccountView
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($student, $newest_enrollment)
  {
    $this->student = $student;
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function lateEnrollmentPrintMessage()
  {
    return parent::lateEnrollmentPrintMessage();
  }
}
