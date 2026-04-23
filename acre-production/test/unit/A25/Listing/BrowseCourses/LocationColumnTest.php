<?php

class test_unit_A25_Listing_BrowseCourses_LocationColumnTest extends
		test_Framework_UnitTestCase
{
    /**
	 * @test
	 */
	public function displaysLocationNameAndCity()
	{
		$report = new unit_LocationColumn_BrowseCourses();
		$course = new unit_LocationColumn_CourseWithLocation();
		$course->course_id = 77;
		$result = $report->locationColumn($course);
		$this->assertEquals('<a href="'
				. A25_Link::withoutSef('course-info?course_id=' . $course->course_id)
				. '">' . $course->getLocationName() . '</a>, '
				. $course->getCityName(),
				$result);
    }
}

class unit_LocationColumn_BrowseCourses extends A25_Listing_BrowseCourses
{
	public function locationColumn(A25_Record_Course $course)
	{
		return parent::locationColumn($course);
	}
}

class unit_LocationColumn_CourseWithLocation extends A25_Record_Course
{
	public function getLocationName()
	{
		return "Mile High Stadium";
	}
	public function getCityName()
	{
		return "Denver";
	}
}