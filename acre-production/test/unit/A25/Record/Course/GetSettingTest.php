<?php

class test_unit_A25_Record_Course_GetSettingTest
		extends test_Framework_UnitTestCase
{
	private $course;
	private $location;

	public function setUp()
	{
		parent::setUp();

		// Make location with no parent
		$this->location = $this->getMock('A25_Record_Location', array('settingParent'));
		$this->location->expects($this->any())
				->method('settingParent')
				->will($this->returnValue(false));

		// Make course with location as its parent
		$this->course = $this->getMock('A25_Record_Course', array('settingParent'));
		$this->course->expects($this->any())
				->method('settingParent')
				->will($this->returnValue($this->location));
	}

	/**
	 * @test
	 */
	public function getCourseSetting()
	{
		$value = 7;
		$fieldName = 'fee';

		$this->course->$fieldName = $value;

		$this->assertEquals($value, $this->course->getSetting($fieldName));
	}

	/**
	 * @test
	 */
	public function getLocationSettingIfCourseSettingIsNull()
	{
		$value = 7;
		$fieldName = 'fee';

		$this->course->$fieldName = null;
		$this->location->$fieldName = $value;

		$this->assertEquals($value, $this->course->getSetting($fieldName));
	}

	/**
	 * @test
	 */
	public function returnsZeroWhenSettingIsNull()
	{
		$fieldName = 'fee';

		$this->course->$fieldName = null;
		$this->location->$fieldName = null;

		$this->assertEquals(0, $this->course->getSetting($fieldName));
	}
}