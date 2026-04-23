<?php

class unit_FindLeafSettingTest_A25_SettingDetector_DoesNotImplement_HasSetting
{
	public $parent;

	public function getSetting($fieldName)
	{
		$detector = new A25_SettingsDetector();
		return $detector->findLeafSetting($this, $fieldName);
	}

	public function settingParent()
	{
		return $this->parent;
	}
}

class unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings
		extends unit_FindLeafSettingTest_A25_SettingDetector_DoesNotImplement_HasSetting
		implements A25_Interface_HaveSettings
{
}

class test_unit_A25_SettingDetector_FindLeafSettingTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getsSetting()
	{
		$value = 7;
		$fieldName = 'theNameOfAField';

		$hasSettings = new unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings();
		
		$hasSettings->$fieldName = $value;

		$detector = new A25_SettingsDetector();
		$this->assertEquals($value, $detector->findLeafSetting($hasSettings, $fieldName));
	}

	/**
	 * @test
	 */
	public function getsParentSettingIfValueIsNull()
	{
		$value = 7;
		$fieldName = 'theNameOfAField';

		$hasSettings = new unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings();
		$hasSettingsParent = new unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings();

		$hasSettings->$fieldName = null;
		$hasSettings->parent = $hasSettingsParent;
		$hasSettingsParent->$fieldName = $value;

		$detector = new A25_SettingsDetector();
		$this->assertEquals($value, $detector->findLeafSetting($hasSettings, $fieldName));
	}

	/**
	 * @test
	 */
	public function returnsZeroIfValueIsNullAndNoParent()
	{
		$fieldName = 'theNameOfAField';

		$hasSettings = new unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings();

		$hasSettings->$fieldName = null;
		$hasSettings->parent = null;

		$detector = new A25_SettingsDetector();
		$this->assertEquals(0, $detector->findLeafSetting($hasSettings, $fieldName));
	}

	/**
	 * @test
	 */
	public function returnsZeroIfValueIsNullAndParentIsNotA25_Interface_HaveSettings()
	{
		$value = 7;
		$fieldName = 'theNameOfAField';

		$hasSettings = new unit_FindLeafSettingTest_A25_SettingDetector_Implements_HaveSettings();
		$hasSettingsParent = new unit_FindLeafSettingTest_A25_SettingDetector_DoesNotImplement_HasSetting();

		$hasSettings->$fieldName = null;
		$hasSettings->parent = $hasSettingsParent;
		$hasSettingsParent->$fieldName = $value;

		$detector = new A25_SettingsDetector();
		$this->assertEquals(0, $detector->findLeafSetting($hasSettings, $fieldName));
	}
}
