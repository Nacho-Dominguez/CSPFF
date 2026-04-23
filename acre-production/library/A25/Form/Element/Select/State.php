<?php

class A25_Form_Element_Select_State extends A25_Form_Element_Select_FromTable
{
	public function __construct($name)
	{
		parent::__construct($name,'jos_us_state','state_code','state_name');
		$this->setLabel('State');
	}
}