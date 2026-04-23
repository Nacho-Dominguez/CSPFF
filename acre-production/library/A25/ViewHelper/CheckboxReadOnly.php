<?php
/**
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_CheckboxReadOnly {
    public function checkboxReadOnly($name, $value = null, $attribs = null)
	{
		if ($value)
			$value = 'Yes';
		else
			$value = 'No';
		return $value;
	}
}
