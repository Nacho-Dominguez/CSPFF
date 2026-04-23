<?php

class test_unit_A25_Record_Location_SettingParentTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getLocationParent()
	{
		$locationParent = new A25_Record_LocationParent();
		$locationParent->location_id = 1;
		$location = new A25_Record_Location();
		$location->Parent = $locationParent;
		$location->location_id = $locationParent->location_id + 1;

		$actualLocationParent = $location->settingParent();

		$this->assertEquals($locationParent->location_id,
				$actualLocationParent->location_id);
	}

	/**
	 * @test
	 */
	public function returnNullIfNoParent()
	{
		$location = new A25_Record_Location();
		$location->Parent = null;

		$this->assertNull($location->settingParent());
	}
}