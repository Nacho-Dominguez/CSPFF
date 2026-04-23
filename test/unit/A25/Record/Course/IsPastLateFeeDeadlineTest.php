<?php

class test_unit_A25_Record_Course_IsPastLateFeeDeadlineTest extends
		test_Framework_UnitTestCase
{
	private $location;
	private $course;
	
	/**
	 * @todo-soon The test setup is more complicated than necessary.  Rather
	 * than creating a location and mocking Course to return the location, just
	 * put the setting directly in Course, so that we don't
	 * even need a Location object in this test.
	 */
	public function setUp()
	{
		parent::setUp();
		$this->location = new A25_Record_Location();
		$this->location->late_fee_deadline = 9;

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
		$this->course->setCourseTime(strtotime($this->location->late_fee_deadline
				. ' hours - 2 minute'));
		$this->assertTrue($this->course->isPastLateFeeDeadline());
    }
	/**
	 * @test
	 */
	public function itHasNot()
	{
		$this->course->setCourseTime(strtotime($this->location->late_fee_deadline
				. ' hours + 2 minutes'));
		$this->assertFalse($this->course->isPastLateFeeDeadline());
    }
}
?>
