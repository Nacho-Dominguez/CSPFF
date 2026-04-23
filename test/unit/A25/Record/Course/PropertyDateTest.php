<?php

/**
 * A25_Record_Course->date is a 'magic' property which actually is a facade over
 * course_start_date.  Rather than having its own field, setting 'date' changes
 * course_start_date, and it is always calculated from whatever is in
 * course_start_date when 'get' is executed on the 'date' property.
 */
class test_unit_A25_Record_Course_PropertyDateTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function calculatesDatePropertyFromCourseStartDate()
	{
		$course = new A25_Record_Course();
		$course->course_start_date = '2011-10-21 0:00:00';
		$this->assertEquals('10/21/2011', $course->date);
	}
	
	/**
	 * @test
	 */
	public function setsDateOnCourseStartDateWhenItWasEmpty()
	{
		$course = new A25_Record_Course();
		$course->date = '10/21/2011';

		$this->assertEquals('2011-10-21 0:00:00', $course->course_start_date);
	}

	/**
	 * @test
	 */
	public function setsDateOnCourseStartDateWhileMaintainingTime()
	{
		$course = new A25_Record_Course();

		// Set the time so that we can verify that it does not change:
		$time = '8:23:00';
		$course->course_start_date = "1969-12-31 $time";
		
		$course->date = '10/21/2011';

		$this->assertEquals("2011-10-21 $time", $course->course_start_date);
	}
	
	/**
	 * @test
	 */
	public function setsDateWith2DigitYear1DigitMonth1DigitDay()
	{
		$course = new A25_Record_Course();
		$course->date = '6/1/11';

		$this->assertEquals('2011-06-01 0:00:00', $course->course_start_date);
	}
	
	/**
	 * @test
	 */
	public function setsDateWithoutYear()
	{
		$course = new A25_Record_Course();
		$course->date = '6/1';

		$this->assertEquals(date('Y') . '-06-01 0:00:00', $course->course_start_date);
	}	
	
	/**
	 * @test
	 */
	public function setsDateWithSingleDigitYear()
	{
		$course = new A25_Record_Course();
		$course->date = '6/1/7';

		$this->assertEquals('2007-06-01 0:00:00', $course->course_start_date);
	}
}
