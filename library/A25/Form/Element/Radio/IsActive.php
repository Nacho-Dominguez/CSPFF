<?php

class A25_Form_Element_Radio_IsActive extends A25_Form_Element_Radio
{
	public function __construct($name, database $db)
	{
		parent::__construct($name);
		$this->setLabel('Active')
				->addMultiOptions(array('1'=>'Active','0'=>'Inactive'));
	}
}
?>
