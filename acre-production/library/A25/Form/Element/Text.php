<?php

class A25_Form_Element_Text extends A25_Form_Element
{
	public function __construct($name)
	{
		parent::__construct(new Zend_Form_Element_Text($name));
	}
}