<?php

class test_unit_A25_View_Student_Account_Online_CourseInfoTest extends
    test_Framework_UnitTestCase
{
  private $student;
  public function setUp()
  {
    parent::setUp();
    
    $this->student = $this->getMock('A25_Record_Student',
        array('getNewestEnrollment', 'getAccountBalance'));
  }
  /**
   * @test
   */
  public function noEnrollment()
  {
    $this->student->expects($this->any())->method('getNewestEnrollment')->will($this->returnValue(null));
    $online = $this->getMock('OnlineWithCourseInfoExposed',
        array('enrollInACourse'), array($this->student));
    $online->expects($this->never())->method('upcomingCourseMessage');
    $online->expects($this->never())->method('registeredMessage');
    $online->expects($this->once())->method('enrollInACourse');
    $online->expects($this->never())->method('completeMessage');
    $online->courseInfo();
  }
  /**
   * @test
   */
  public function inactiveEnrollment()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_canceled;
    $this->student->expects($this->any())->method('getNewestEnrollment')->will($this->returnValue($enroll));
    $online = $this->getMock('OnlineWithCourseInfoExposed',
        array('enrollInACourse'), array($this->student));
    $online->expects($this->never())->method('upcomingCourseMessage');
    $online->expects($this->never())->method('registeredMessage');
    $online->expects($this->once())->method('enrollInACourse');
    $online->expects($this->never())->method('completeMessage');
    $online->courseInfo();
  }
  /**
   * @test
   */
  public function completeEnrollment()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(true));
    $enroll->status_id = A25_Record_Enroll::statusId_completed;
    $this->student->expects($this->any())->method('getNewestEnrollment')->will($this->returnValue($enroll));
    $online = $this->getMock('OnlineWithCourseInfoExposed',
        array('completeMessage'), array($this->student));
    $online->expects($this->never())->method('upcomingCourseMessage');
    $online->expects($this->never())->method('registeredMessage');
    $online->expects($this->never())->method('enrollInACourse');
    $online->expects($this->once())->method('completeMessage');
    $online->courseInfo();
  }
  /**
   * @test
   */
  public function activeIncompleteAndPaid()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    $this->student->expects($this->any())->method('getNewestEnrollment')->will($this->returnValue($enroll));
    $this->student->expects($this->any())->method('getAccountBalance')->will($this->returnValue(0));
    $online = $this->getMock('OnlineWithCourseInfoExposed',
        array('upcomingCourseMessage'), array($this->student));
    $online->expects($this->once())->method('upcomingCourseMessage');
    $online->expects($this->never())->method('registeredMessage');
    $online->expects($this->never())->method('enrollInACourse');
    $online->expects($this->never())->method('completeMessage');
    $online->courseInfo();
  }
  /**
   * @test
   */
  public function activeIncompleteNotPaid()
  {
    $enroll = new A25_Record_Enroll();
    $enroll->status_id = A25_Record_Enroll::statusId_registered;
    $this->student->expects($this->any())->method('getNewestEnrollment')->will($this->returnValue($enroll));
    $this->student->expects($this->any())->method('getAccountBalance')->will($this->returnValue(59));
    $online = $this->getMock('OnlineWithCourseInfoExposed',
        array('registeredMessage'), array($this->student));
    $online->expects($this->never())->method('upcomingCourseMessage');
    $online->expects($this->once())->method('registeredMessage');
    $online->expects($this->never())->method('enrollInACourse');
    $online->expects($this->never())->method('completeMessage');
    $online->courseInfo();
  }
}

class OnlineWithCourseInfoExposed extends A25_View_Student_Account_Online
{
  public function courseInfo()
  {
    return parent::courseInfo();
  }
}