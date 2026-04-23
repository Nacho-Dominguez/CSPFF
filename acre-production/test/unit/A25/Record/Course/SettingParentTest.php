<?php

class test_unit_A25_Record_Course_SettingParentTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getCourseParent()
	{
		$location = new A25_Record_Location();
		$location->location_id = 1;
		$course = new A25_Record_Course();
		$course->Location = $location;

		$courseParent = $course->settingParent();

		$this->assertEquals($location->location_id,
				$courseParent->location_id);
	}

	/**
	 * @test
	 */
	public function returnNullIfNoParent()
	{
		$course = new A25_Record_Course();

		$this->assertNull($course->settingParent());
	}
}