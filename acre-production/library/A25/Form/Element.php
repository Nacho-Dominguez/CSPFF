<?php

class A25_Form_Element extends A25_Form_ElementWrapper
{
	public function __construct(Zend_Form_Element $element)
	{
		parent::__construct($element);
		$this->setLabel($this->convertToReadableLabel($element->getName()));
	}
	public function setReadOnly()
	{
		$this->_element->helper = 'readOnly';
		return $this;
	}
	private function convertToReadableLabel($name)
	{
		return ucwords(str_replace('_', ' ', $name));
	}
}
?>
