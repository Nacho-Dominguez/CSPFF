<?php

/**
 * A25_Record_Course->start_time is a 'magic' property which actually is a
 * facade over course_start_date.  Rather than having its own field, setting
 * 'start_time' changes course_start_date, and it is always calculated from
 * whatever is in course_start_date when 'get' is executed on the 'start_time'
 * property.
 */
class test_unit_A25_Record_Course_PropertyStartTimeTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function calculatesStartTimePropertyFromCourseStartDate()
	{
		$course = new A25_Record_Course();
		$course->course_start_date = '2011-10-21 13:15:00';
		$this->assertEquals('01:15 PM', $course->start_time);
	}

	/**
	 * @test
	 */
	public function setsStartTimeOnCourseStartDateWhenItWasEmpty()
	{
		$course = new A25_Record_Course();

		$time = '8:23:00';
		$course->start_time = $time;

		$this->assertEquals(date('Y-m-d') . " $time",
				$course->course_start_date);
	}

	/**
	 * @test
	 */
	public function setsStartTimeWhileMaintainingDate()
	{
		$course = new A25_Record_Course();

		// Set the date so that we can verify that it does not change:
		$date = '2011-10-21';
		$course->course_start_date = "$date 00:00:00";

		$time = '8:23:00';
		$course->start_time = $time;

		$this->assertEquals("$date $time", $course->course_start_date);
	}
	
	/**
	 * @test
	 */
	public function setsStartTimeWithAmPm()
	{
		$course = new A25_Record_Course();

		$course->start_time = '5:23 PM';

		$this->assertEquals(date('Y-m-d') . ' 17:23:00',
				$course->course_start_date);
	}	
	
	/**
	 * @test
	 */
	public function setsStartTimeWithoutHoursAndWithLowerCasePm()
	{
		$course = new A25_Record_Course();

		$course->start_time = '5pm';

		$this->assertEquals(date('Y-m-d') . ' 17:00:00',
				$course->course_start_date);
	}
}
