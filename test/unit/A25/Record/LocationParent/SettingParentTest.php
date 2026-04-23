<?php

class test_unit_A25_Record_LocationParent_SettingParentTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function returnNull()
	{
		$locationParent = new A25_Record_LocationParent();

		$this->assertNull($locationParent->settingParent());
	}
}