<?php

class test_unit_A25_View_Student_Account_KickOutIfNecessaryTest extends
    test_Framework_UnitTestCase
{
  private $course;
  private $enroll;
  private $account;
  private $kickout;
  
  private function setUpTests()
  {
    $student = new A25_Record_Student();
    
    $this->course = new A25_Record_Course();
    $this->course->course_start_date = A25_Functions::formattedDateTime('1 hour');
    
    $this->enroll = new A25_Record_Enroll();
    $this->enroll->status_id = A25_Record_Enroll::statusId_registered;
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime('-1 hour');
    $this->enroll->Course = $this->course;
    $this->enroll->Student = $student;
    
    $this->kickout = $this->getMock('A25_Remind_Students_KickOut', array('sendToIndividual'));
    
    $factory = $this->getMock('A25_Factory_PhysicalLocation', array('KickOut'));
    $factory->expects($this->any())->method('KickOut')
        ->will($this->returnValue($this->kickout));
    
    A25_DI::setFactory($factory);
    
    $this->account = A25_DI::Factory()->Account($student);
  }
  
	/**
	 * @test
	 */
	public function kicksOutIfNecessary()
  {
    $this->setUpTests();
    
    $this->kickout->expects($this->once())->method('sendToIndividual');
    
    $this->account->kickOutIfNecessary();
  }
  
	/**
	 * @test
	 */
	public function DoesNotKickOutIfCourseIsPast()
  {
    $this->setUpTests();
    $this->course->course_start_date = A25_Functions::formattedDateTime('-1 hour');
    
    $this->kickout->expects($this->never())->method('sendToIndividual');
    
    $this->account->kickOutIfNecessary();
  }
  
	/**
	 * @test
	 */
	public function DoesNotKickOutIfStatusNotRegistered()
  {
    $this->setUpTests();
    $this->enroll->status_id = A25_Record_Enroll::statusId_student;
    
    $this->kickout->expects($this->never())->method('sendToIndividual');
    
    $this->account->kickOutIfNecessary();
  }
  
	/**
	 * @test
	 */
	public function DoesNotKickOutIfKickOutDateNotPast()
  {
    $this->setUpTests();
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime('1 hour');
    
    $this->kickout->expects($this->never())->method('sendToIndividual');
    
    $this->account->kickOutIfNecessary();
  }
}
