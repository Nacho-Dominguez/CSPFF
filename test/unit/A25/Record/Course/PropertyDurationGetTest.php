<?php

class Course_WithoutSetModifications extends A25_Record_Course
{
	protected function setDuration($value, $load = true)
	{
		$this->_set('duration', $value, $load);
	}
}

class test_unit_A25_Record_Course_PropertyDurationGetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function doesNothingToProperlyFormattedHoursAndMinutes()
	{
		$course = new Course_WithoutSetModifications();
		$course->duration = '4:30';
		$this->assertEquals('4:30', $course->duration);
	}
	/**
	 * This is normally the form that the data will be in when retrieving from
	 * the database.
	 * 
	 * @test
	 */
	public function stripsOffSeconds()
	{
		$course = new Course_WithoutSetModifications();
		$course->duration = '4:30:00';
		$this->assertEquals('4:30', $course->duration);
	}
}