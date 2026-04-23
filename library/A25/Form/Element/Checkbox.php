<?php

class A25_Form_Element_Checkbox extends A25_Form_Element
{
	public function __construct($name)
	{
		parent::__construct(new Zend_Form_Element_Checkbox($name));
	}
	public function setReadOnly()
	{
		$this->_element->helper = 'checkboxReadOnly';
		return $this;
	}
}
