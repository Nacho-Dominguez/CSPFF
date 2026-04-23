<?php
/**
 * This renders a fieldset as a table row, instead of the normal <fieldset>.  It
 * is useful for putting multiple fields in 1 row in a table-style form.  An
 * example is the 'When' display group in A25_Form_Record_Course.
 * 
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_Fieldset extends Zend_View_Helper_FormElement {
    public function fieldset($name, $content, $attribs = null)
	{
        $info = $this->_getInfo($name, $content, $attribs);
        extract($info);

        // get legend
        $legend = '';
        if (isset($attribs['legend'])) {
            $legendString = trim($attribs['legend']);
            if (!empty($legendString)) {
                $legend = (($escape) ? $this->view->escape($legendString) : $legendString);
            }
            unset($attribs['legend']);
        }

        // get id
        if (!empty($id)) {
            $id = ' id="' . $this->view->escape($id) . '"';
        } else {
            $id = '';
        }

        // render fieldset
        $xhtml = '<tr'
               . $id
               . $this->_htmlAttribs($attribs)
               . '><td>'
               . $legend
			   . '</td><td>'
               . $content
               . '</td></tr>';

        return $xhtml;
	}
}