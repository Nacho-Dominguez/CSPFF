<?php

class test_unit_A25_CourseRunner_MakeTheEnrollmentTest extends
		test_Framework_UnitTestCase
{
	private $course;
	private $student;
	private $runner;

	public function setUp()
	{
		parent::setUp();
		$this->course = new A25_Record_Course();
		$this->student = $this->mock('A25_Record_Student');

		$this->runner = new unit_MakeTheEnrollmentTest_A25_CourseRunner($this->student, $this->course);
	}
	/**
	 * @test
	 */
	public function callsEnrollInCourse()
	{
		$_REQUEST['hear_about_id'] = 1;
		$_REQUEST['reason_id'] = 2;
		$_REQUEST['court_id'] = 4;
		$_REQUEST['reason_other'] = 5;

		$this->student->expects($this->once())->method('enrollInCourse')->with(
				$this->course,
				$_REQUEST['hear_about_id'],
				$_REQUEST['reason_id'],
				false,
				$_REQUEST['court_id'],
				$_REQUEST['reason_other'])
			->will($this->returnValue(99));

		$this->runner->makeTheEnrollment();
	}

	/**
	 * @test
	 */
	public function setsEnrollProperty()
	{
		$this->student->expects($this->any())->method('enrollInCourse')->will($this->returnValue(99));

		$this->runner->makeTheEnrollment();

		$this->assertEquals(99, $this->runner->getEnroll());
	}
}

class unit_MakeTheEnrollmentTest_A25_CourseRunner extends A25_CourseRunner
{
	public $called_waiveSurchargeIfNecessary = false;

	public function makeTheEnrollment()
	{
		return parent::makeTheEnrollment();
	}
	public function getEnroll()
	{
		return $this->_enroll;
	}
}