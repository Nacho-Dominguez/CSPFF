<?php

class test_unit_A25_Record_Course_PropertyEndTimeSetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsDuration()
	{
		$course = new Course_WithoutGetModifications();
		$course->start_time = '11:00 AM';
		$course->end_time = '12:24 PM';
		$this->assertEquals('1:24', $course->duration);
	}
	/**
	 * @test
	 */
	public function setsDurationWhenOver12Hours()
	{
		$course = new Course_WithoutGetModifications();
		$course->start_time = '8:00 AM';
		$course->end_time = '9:13 PM';
		$this->assertEquals('13:13', $course->duration);
	}
}