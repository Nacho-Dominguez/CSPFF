<?php

class test_unit_A25_Listing_BrowseCourses_FormatRowTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function dateTimeIsCorrect()
	{
		$report = new unit_Listing_BrowseCourses();
		$course = new A25_Record_Course();
		$course->course_start_date = '2011-01-01 08:00:00';
		$result = $report->formatRow($course);
		$this->assertEquals('<div style="min-width: 60px;">' . $course->prettierDateTime() . '</div>', $result['Date/Time']);
    }
    /**
	 * @test
	 */
	public function locationColumnIsCorrect()
	{
		$report = new unit_Listing_BrowseCourses();
		$course = new unit_BrowseCourses_CourseWithLocation();
		$result = $report->formatRow($course);
		$this->assertEquals($report->locationColumn($course), $result['Location']);
    }
    /**
	 * @test
	 */
	public function actionColumnCorrect()
	{
		$report = new unit_Listing_BrowseCourses();
		$course = new A25_Record_Course();
		$result = $report->formatRow($course);
		$this->assertEquals('<div style="max-width: 310px; float: right;">' . $report->actionColumn($course) . '</div>', $result['Action']);
    }
}

class unit_Listing_BrowseCourses extends A25_Listing_BrowseCourses
{
	public function formatRow(A25_DoctrineRecord $course)
	{
		return parent::formatRow($course);
	}
	public function actionColumn(A25_Record_Course $course)
	{
		return parent::actionColumn($course);
	}
	public function locationColumn(A25_Record_Course $course)
	{
		return parent::locationColumn($course);
	}
}

class unit_BrowseCourses_CourseWithLocation extends A25_Record_Course
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