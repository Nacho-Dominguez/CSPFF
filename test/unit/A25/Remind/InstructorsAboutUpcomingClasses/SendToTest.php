<?php

class test_unit_A25_Remind_InstructorsAboutUpcomingClasses_SendToTest extends
		test_Framework_UnitTestCase
{
	private $course;
	private $courses;
	private $instructor;

	public function setUp()
	{
		parent::setUp();
		$this->course = $this->getMock('A25_Record_Course', array('save'));
		$this->course->Location = new A25_Record_Location();
		$this->instructor = $this->getMock('A25_Record_User', array('sendMessage'));
		$this->instructor->id = 27;
		$this->course->Instructor = $this->instructor;
		$this->courses[] = $this->course;
	}
  
	/**
	 * @test
	 */
	public function MarksEmailWasSent()
	{
		$reminder = new InstructorsAboutUpcomingClassesWithMethodsExposed();
		$reminder->sendTo($this->courses);
		$this->assertEquals(1,$this->course->instructor_reminder_sent);
	}
	
	/**
	 * @test
	 */
	public function SendsMessageToInstructor1()
	{
		$this->instructor->expects($this->once())->method('sendMessage')->with(
			A25_Remind_InstructorsAboutUpcomingClasses::emailSubject(),
			A25_Remind_InstructorsAboutUpcomingClasses::emailBody($this->course));

		$reminder = new InstructorsAboutUpcomingClassesWithMethodsExposed();
		$reminder->sendTo($this->courses);
	}

	/**
	 * @test
	 */
	public function SendsMessageToInstructor2()
	{
		$instructor2 = $this->getMock('A25_Record_User', array('sendMessage'));
		$instructor2->email = 'instructor2@thomasalbright.com';
		$instructor2->id = 56;
		$this->course->Instructor2 = $instructor2;
		$instructor2->expects($this->once())->method('sendMessage')->with(
			A25_Remind_InstructorsAboutUpcomingClasses::emailSubject(),
			A25_Remind_InstructorsAboutUpcomingClasses::emailBody($this->course));

		$reminder = new InstructorsAboutUpcomingClassesWithMethodsExposed();
		$reminder->sendTo($this->courses);
	}
}

class InstructorsAboutUpcomingClassesWithMethodsExposed extends 
    A25_Remind_InstructorsAboutUpcomingClasses
{
  public function sendTo($courses) {
    return parent::sendTo($courses);
  }
}