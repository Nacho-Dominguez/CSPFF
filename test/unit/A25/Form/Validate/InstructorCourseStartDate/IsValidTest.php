<?php
class test_unit_A25_Form_Validate_InstructorCourseStartDate_IsValidTest
		extends test_Framework_UnitTestCase
{
	private $user;

	public function setUp()
	{
		$this->user = new A25_Record_User();
		$this->user->usertype = 'Instructor';
		A25_DI::setUser($this->user);
	}
	/**
	 * @test
	 */
	public function allowsInstructorToSaveWithinDeadline_ifCourseDateDoesNotChange()
	{
		$originalDate = $this->daysAfterDeadline(-1);

		$validator = new A25_Form_Validate_InstructorCourseStartDate($originalDate);
		$this->assertTrue($validator->isValid($originalDate));
	}
	/**
	 * @test
	 */
	public function allowsNonInstructorToCreateNewClass_BeforeDeadline()
	{
		$this->user->usertype = 'Not Instructor';

		$newDate = $this->daysAfterDeadline(-1);

		$validator = new A25_Form_Validate_InstructorCourseStartDate(null);
		$this->assertTrue($validator->isValid($newDate));
	}

	/**
	 * @test
	 */
	public function DeniesInstructorToChangeExistingCourseDate_ifNewValueIsBeforeDeadline()
	{
		$originalDate = $this->daysAfterDeadline(1);
		$newDate = $this->daysAfterDeadline(-1);

		$validator = new A25_Form_Validate_InstructorCourseStartDate($originalDate);
		$this->assertFalse($validator->isValid($newDate));
	}

	/**
	 * @test
	 */
	public function allowsInstructorToSaveNewCourse_AfterDeadline()
	{		
		$newDate = $this->daysAfterDeadline(1);

		$validator = new A25_Form_Validate_InstructorCourseStartDate(null);
		$this->assertTrue($validator->isValid($newDate));
	}

	/**
	 * @test
	 */
	public function deniesInstructorDateChange_ifOriginalDateIsAlreadyWithinDeadline()
	{
		$originalDate = $this->daysAfterDeadline(-1);
		$newDate = $this->daysAfterDeadline(1);

		$validator = new A25_Form_Validate_InstructorCourseStartDate($originalDate);
		$this->assertFalse($validator->isValid($newDate));
	}
	private function daysAfterDeadline($days)
	{
		return date('Y-m-d', strtotime(
				(PlatformConfig::instructorClassCreationDeadline+$days)
				. ' days'));
	}
}