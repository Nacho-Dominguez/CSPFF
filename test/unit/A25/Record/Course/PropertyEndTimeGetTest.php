<?php

class Course_WithNullDuration extends A25_Record_Course
{
	protected function getDuration($load = true) {
		return null;
	}
}

class test_unit_A25_Record_Course_PropertyEndTimeGetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function calculatesFromStartTimeAndDuration()
	{
		$course = new A25_Record_Course;
		$course->start_time = '8:00 AM';
		$course->duration = '5:30';
		$this->assertEquals('01:30 PM', $course->end_time);
	}
	/**
	 * @test
	 */
	public function returnsNullIfStartTimeIsNotSet()
	{
		$course = new A25_Record_Course;
		$course->duration = '4:30';
		$this->assertEquals(null, $course->end_time);
	}
	/**
	 * @test
	 */
	public function returnsNullIfDurationIsNotSet()
	{
		$course = new Course_WithNullDuration;
		$course->start_time = '8:00';
		$this->assertEquals(null, $course->end_time);
	}
}