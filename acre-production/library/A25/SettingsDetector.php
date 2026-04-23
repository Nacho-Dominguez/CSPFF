<?php

class A25_SettingsDetector
{
	/**
	 * @param A25_Interface_HaveSettings $hasSetting
	 * @param string $fieldName
	 */
	public function findLeafSetting(A25_Interface_HaveSettings $hasSetting, $fieldName)
	{
		$value = $hasSetting->$fieldName;
    if ($this->isInvalidValue($value)) {
			$parent = $hasSetting->settingParent();
      if (!is_null($parent) && $parent instanceof A25_Interface_HaveSettings) {
          return $parent->getSetting($fieldName);
      } else {
          return 0;
      }
    }
		return $value;
    }
	
	protected function isInvalidValue($value)
	{
		return $value == null;
  }
}
