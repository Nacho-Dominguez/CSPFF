<?php
/**
 * This renders a FormLabel as normal, unless the label is 'Save'.  This way,
 * we don't label a submit button.
 * 
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_FormLabel extends Zend_View_Helper_FormLabel {
    public function formLabel($name, $value = null, array $attribs = array())
	{
		if ($value == 'Save')
			$value = null;
		
		return parent::formLabel($name, $value, $attribs);
	}
}