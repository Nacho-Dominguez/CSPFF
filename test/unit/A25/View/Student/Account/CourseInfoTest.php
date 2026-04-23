<?php

class test_unit_A25_View_Student_Account_CourseInfoTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function whenNoEnrollment_callsEnrollInACourse()
  {
    $view = $this->makeView(null);
    
    $view->expects($this->never())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->once())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentCanceled_callsEnrollInACourse()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_canceled;
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->never())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->once())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentKickedOut_callsKickedOut()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_kickedOut;
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->never())->method('upcomingCourseMessage');
    $view->expects($this->once())->method('kickedOutMessage');
    $view->expects($this->never())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentKickedOutButCourseIsPast_callsEnrollInACourse()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_kickedOut;
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(true));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->never())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->once())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentRegistered_callsUpcomingCourseMessage()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_registered;
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->once())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->never())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentStudent_callsUpcomingCourseMessage()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_student;
    $enroll->expects($this->any())->method('courseIsPast')->will($this->returnValue(false));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->once())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->never())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  /**
   * @test
   */
  public function whenEnrollmentCompleted_callsEnrollInACourse()
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('courseIsPast'));
    $enroll->status_id = A25_Record_Enroll::statusId_completed;
    $enroll->expects($this->any())->method('courseIsPast')
        ->will($this->returnValue(true));
    
    $view = $this->makeView($enroll);
    
    $view->expects($this->never())->method('upcomingCourseMessage');
    $view->expects($this->never())->method('kickedOutMessage');
    $view->expects($this->once())->method('enrollInACourse');
    
    $view->courseInfo();
  }
  
  private function makeView($enroll)
  {
    return $this->getMock('CourseInfoTest_AccountView',
        array('upcomingCourseMessage', 'enrollInACourse', 'kickedOutMessage'), array($enroll));
  }
}

class CourseInfoTest_AccountView
    extends A25_View_Student_Account_PhysicalLocation
{
  public function __construct($newest_enrollment)
  {
    $this->newest_enrollment = $newest_enrollment;
  }
  
  public function courseInfo()
  {
    return parent::courseInfo();
  }
}
