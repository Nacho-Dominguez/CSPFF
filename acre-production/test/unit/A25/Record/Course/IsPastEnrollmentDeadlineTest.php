<?php

class test_unit_A25_Record_Course_IsPastEnrollmentDeadlineTest extends
		test_Framework_UnitTestCase
{
	private $location;
	private $course;

	/**
	 * @todo-soon The test setup is more complicated than necessary.  Rather
	 * than creating a location and mocking Course to return the location, just
	 * put the 'enrollment_deadline' setting directly in Course, so that we don't
	 * even need a Location object in this test.
	 */
	public function setUp()
	{
		parent::setUp();
		$this->location = new A25_Record_Location();
		$this->location->enrollment_deadline = '9 hours';

		// Make course with location as its parent
		$this->course = $this->getMock('A25_Record_Course', array('settingParent'));
		$this->course->expects($this->any())
				->method('settingParent')
				->will($this->returnValue($this->location));
		$this->course->Location = $this->location;
	}
    /**
	 * @test
	 */
	public function itHas()
	{
		$this->course->setCourseTime(strtotime($this->location->enrollment_deadline
				. ' - 2 minutes'));
		$this->assertTrue($this->course->isPastEnrollmentDeadline());
    }
	/**
	 * @test
	 */
	public function itHasNot()
	{
		$this->course->setCourseTime(strtotime($this->location->enrollment_deadline));
		$this->assertFalse($this->course->isPastEnrollmentDeadline());
    }
}
?>
