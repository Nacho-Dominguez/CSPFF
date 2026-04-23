<?php

class test_unit_A25_Listing_BrowseCourses_OpenSeatsTest extends
		test_Framework_UnitTestCase
{
    /**
	 * @test
	 */
	public function reportsSeatsLeft()
	{
		$course = new A25_Record_Course();
		$course->course_capacity = 7;

		$report = new unit_OpenSeats_BrowseCourses();
		$this->assertEquals($course->openSeats() . ' seats left',
				$report->openSeats($course));
    }
}

class unit_OpenSeats_BrowseCourses extends A25_Listing_BrowseCourses
{
	public function openSeats(A25_Record_Course $course)
	{
		return parent::openSeats($course);
	}
}