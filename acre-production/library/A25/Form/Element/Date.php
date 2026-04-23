<?php

class A25_Form_Element_Date extends A25_Form_Element
{
	public function __construct($name)
	{
		parent::__construct(new Zend_Form_Element_Text($name));
		$this->addValidator('regex', false,
				array('/^\d{4}-\d\d-\d\d$/'));
		$this->getValidator('regex')->setMessage(
			'Must be formatted YYYY-MM-DD');
	}
}
?>
