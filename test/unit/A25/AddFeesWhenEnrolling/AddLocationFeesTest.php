<?php

class test_unit_A25_AddFeesWhenEnrolling_AddLocationFeesTest
		extends test_Framework_UnitTestCase
{
	private $course;
	private $fee;
	private $location;
	private $order;

	/**
	 * @todo-soon The test setup is more complicated than necessary.  Rather
	 * than creating a location and mocking Course to return the location, just
	 * put the setting directly in Course, so that we don't
	 * even need a Location object in this test.
	 */
	public function setUp()
	{

		// Make location with no parent
		$this->location = $this->getMock('A25_Record_Location', array('settingParent'));
		$this->location->expects($this->any())
				->method('settingParent')
				->will($this->returnValue(false));
		// Setting the course fee in location, this is the more general case
		$this->location->fee = 79;

		// Make course with location as its parent
		$this->course = $this->getMock('A25_Record_Course', array('settingParent'));
		$this->course->expects($this->any())
				->method('settingParent')
				->will($this->returnValue($this->location));

		$this->order = $this->getMock('A25_Record_Order');

		$this->fee = new unit_AddLocationFeesTest_A25_AddFeesWhenEnrolling(
				new A25_Record_Enroll(), $this->order);
	}
	/**
	 * @test
	 */
	public function callsCreateLineItem()
	{
		$this->order->expects($this->once())->method('createLineItem');
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineItemWithTypeCourseFee()
	{

		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee);
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineItemWithCourseFee()
	{
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->location->fee);
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineItemWithCorrectFee_whenExtraFee()
	{
		$extraFee = 10;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->location->fee + $extraFee);
		$this->callAddLocationFees($extraFee);
	}

	private function callAddLocationFees($extraFees)
	{
		$this->fee->addLocationFees($this->course, $extraFees);
	}
}

class unit_AddLocationFeesTest_A25_AddFeesWhenEnrolling
		extends A25_AddFeesWhenEnrolling
{
	public $_orderRecord;

	public function addLocationFees(A25_Record_Course $course, $extraFee)
	{
		return parent::_addLocationFees($course,$extraFee);
	}
}
