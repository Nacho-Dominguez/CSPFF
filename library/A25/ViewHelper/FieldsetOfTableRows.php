<?php
/**
 * This renders a fieldset as a table row, instead of the normal <fieldset>.  It
 * is useful for putting multiple fields in 1 row in a table-style form.  An
 * example is the 'When' display group in A25_Form_Record_Course.
 * 
 * The best article I have found describing ViewHelpers and how they work is:
 * http://devzone.zend.com/article/3412-View-Helpers-in-Zend-Framework
 */
class A25_ViewHelper_FieldsetOfTableRows extends Zend_View_Helper_FormElement {
	private $_sublegend;
	
    /**
     * Set sublegend
     * 
     * @param  string $value 
     * @return Zend_Form_Decorator_Fieldset
     */
    public function setSublegend($value)
    {
        $this->_sublegend = (string) $value;
        return $this;
    }

    /**
     * Get legend
     * 
     * @return string
     */
    public function getSublegend()
    {
        $sublegend = $this->_sublegend;
        if ((null === $sublegend) && (null !== ($element = $this->getElement()))) {
            if (method_exists($element, 'getSublegend')) {
                $sublegend = $element->getSublegend();
                $this->setSublegend($sublegend);
            }
        }
        if ((null === $sublegend) && (null !== ($sublegend = $this->getOption('sublegend')))) {
            $this->setLegend($sublegend);
            $this->removeOption('sublegend');
        }

        return $sublegend;
    }
	
    public function fieldsetOfTableRows($name, $content, $attribs = null)
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
		
        // get sublegend
        $sublegend = '';
        if (isset($attribs['sublegend'])) {
            $sublegendString = trim($attribs['sublegend']);
            if (!empty($sublegendString)) {
                $sublegend = (($escape) ? $this->view->escape($sublegendString) : $sublegendString);
            }
            unset($attribs['sublegend']);
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
               . '><td colspan=2><h3>'
               . $legend . '</h3><p>' . $sublegend . '</p>'
			   . '</td></tr>'
               . $content
				 // Append a blank row for spacing
			   . '<tr><td colspan=2>&nbsp;</td></tr>';

        return $xhtml;
	}
}