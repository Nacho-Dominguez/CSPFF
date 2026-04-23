<?php

class A25_Form_Element_Radio_Gender extends A25_Form_Element_Radio
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setLabel('Sex')
				->addMultiOptions(array('M'=>'Male','F'=>'Female'));
	}
}
?>
