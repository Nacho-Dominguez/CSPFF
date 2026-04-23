<?php

class test_unit_A25_Record_Enroll_SendSpecialNeedsEmailTest extends
		test_Framework_UnitTestCase
{
	private $enroll;
	private $student;
	private $course;

	public function setUp()
	{
		parent::setUp();
		$mailer = $this->mock('A25_Mailer');
		A25_DI::setMailer($mailer);

		$this->student = new A25_Record_Student();
		$this->student->special_needs = "";
		$this->course = new A25_Record_Course();
		$this->course->course_start_date = A25_Functions::formattedDateTime('1 week');
		$this->course->Location = new A25_Record_Location();
		$this->course->Location->location_name = 'A Location Name';
		$this->enroll = new EnrollWithSendSpecialNeedsEmailExposed();
    $this->enroll->Course = $this->course;
    $this->enroll->Student = $this->student;
	}
	/**
	 * @test
	 */
	public function SkipIfEmptyString()
	{
		$this->student->special_needs = "";

		A25_DI::Mailer()->expects($this->never())->method('mail');

		$this->enroll->sendSpecialNeedsEmail();
	}
	/**
	 * @test
	 */
	public function SkipIfNull()
	{
		$this->student->special_needs = null;

		A25_DI::Mailer()->expects($this->never())->method('mail');

		$this->enroll->sendSpecialNeedsEmail();
	}
	/**
	 * @test
	 */
	public function SendIfDefined()
	{
		$instructor = new A25_Record_User();
		$instructor->email = 'instructor email';
		$this->course->Instructor = $instructor;

		$this->student->first_name = 'Jonny';
		$this->student->last_name = 'Johnson';
		$this->student->special_needs = "I have special needs";
		
		$subject = A25_EmailContent::wrapSubject('Notification of special-needs student');
		$body = "A special needs student has enrolled in your class.\n\n" .
					"First name: " . $this->student->first_name . "\n" .
					"Last name: " . $this->student->last_name . "\n" .
					"Special needs: " . $this->student->special_needs . "\n\n" .
					"Course Date: " . $this->course->prettyDateTime() . "\n" .
					"Course Location: " . $this->course->Location->location_name;

		A25_DI::Mailer()->expects($this->at(0))->method('mail')->with(
				ServerConfig::specialNeedsEmailAddress(), $subject, $body);
		A25_DI::Mailer()->expects($this->at(1))->method('mail')->with($instructor->email,
				$subject, $body);

		$this->enroll->sendSpecialNeedsEmail();
	}
	/**
	 * @test
	 */
	public function SkipInstructorEmailIfInstructorNotDefined()
	{
		$this->student->first_name = 'Jonny';
		$this->student->last_name = 'Johnson';
		$this->student->special_needs = "I have special needs";

		$subject = A25_EmailContent::wrapSubject('Notification of special-needs student');
		$body = "A special needs student has enrolled in your class.\n\n" .
					"First name: " . $this->student->first_name . "\n" .
					"Last name: " . $this->student->last_name . "\n" .
					"Special needs: " . $this->student->special_needs . "\n\n" .
					"Course Date: " . $this->course->prettyDateTime() . "\n" .
					"Course Location: " . $this->course->Location->location_name;

		A25_DI::Mailer()->expects($this->once())->method('mail')->with(
				ServerConfig::specialNeedsEmailAddress(), $subject, $body);

		$this->enroll->sendSpecialNeedsEmail();
	}
}

class EnrollWithSendSpecialNeedsEmailExposed extends A25_Record_Enroll
{
	public function sendSpecialNeedsEmail()
	{
		return parent::sendSpecialNeedsEmail();
	}
}
