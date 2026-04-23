<?php
/**
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_ReadOnly {
    public function readOnly($name, $value = null, $attribs = null)
	{
		if (is_null($value))
			$value = '&nbsp;';
		return $value;
	}
}
