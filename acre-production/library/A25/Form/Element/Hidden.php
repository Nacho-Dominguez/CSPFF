<?php

class A25_Form_Element_Hidden extends A25_Form_Element
{
	public function __construct($name)
	{
		parent::__construct(new Zend_Form_Element_Hidden($name));
	}
	public function setReadOnly()
	{
		$this->_element->helper = 'selectReadOnly';
		return $this;
	}
}
?>
