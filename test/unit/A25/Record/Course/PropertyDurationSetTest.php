<?php

class Course_WithoutGetModifications extends A25_Record_Course
{
	protected function getDuration($load = true)
	{
		return $this->_get('duration', $load);
	}
}

class test_unit_A25_Record_Course_PropertyDurationSetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function doesNothingToProperlyFormattedHoursAndMinutes()
	{
		$course = new Course_WithoutGetModifications();
		$course->duration = '4:30';
		$this->assertEquals('4:30', $course->duration);
	}
	/**
	 * @test
	 */
	public function doesNothingToProperlyFormattedHoursAndMinutesAndSeconds()
	{
		$course = new Course_WithoutGetModifications();
		$course->duration = '4:20:10';
		$this->assertEquals('4:20:10', $course->duration);
	}
	
	/**
	 * @test
	 */
	public function changesSimpleNumberToFullHours()
	{
		$course = new Course_WithoutGetModifications();
		$course->duration = '4';
		$this->assertEquals('4:00', $course->duration);
	}
	/**
	 * @test
	 * 
	 * @expectedException A25_Exception_IllegalArgument
	 */
	public function throwsExceptionWithUnexpectedInput()
	{
		$course = new Course_WithoutGetModifications();
		$course->duration = 'this is unexpected input';
	}
}