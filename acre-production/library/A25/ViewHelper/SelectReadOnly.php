<?php
/**
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_SelectReadOnly extends Zend_View_Helper_FormElement {
    public function selectReadOnly($name, $value = null, $attribs = null,
        $options = null)
    {
		foreach ((array) $options as $opt_value => $opt_label) {
			if ($value == $opt_value) {
				return $opt_label;
			}
		}
		return '&nbsp;';
	}
}
?>
