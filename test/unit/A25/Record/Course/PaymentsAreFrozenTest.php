<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_PaymentsAreFrozenTest extends
		test_Framework_UnitTestCase
{
	private $location;
	private $course;

	/**
	 * @todo-soon The test setup is more complicated than necessary.  Rather
	 * than creating a location and mocking Course to return the location, just
	 * put the 'payment_deadline' setting directly in Course, so that we don't
	 * even need a Location object in this test.
	 */
	public function setUp()
	{
		parent::setUp();
		$this->location = new A25_Record_Location();
		$this->location->payment_deadline = 9;

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
	public function inBetweenPaymentDeadlineAndCourse()
	{
		$this->course->setCourseTime(strtotime($this->location->payment_deadline
				. ' hours - 2 minute'));
		$this->assertTrue($this->course->paymentsAreFrozen());
    }
	/**
	 * @test
	 */
	public function beforePaymentDeadline()
	{
		$this->course->setCourseTime(strtotime($this->location->payment_deadline
				. ' hours + 2 minute'));
		$this->assertFalse($this->course->paymentsAreFrozen());
    }
	/**
	 * @test
	 */
	public function afterCourse()
	{
		$this->course->setCourseTime(strtotime('-2 minute'));
		$this->assertFalse($this->course->paymentsAreFrozen());
    }
}
?>
