<?php

// $todo-jon-low-small: This shouldn't be a subclass of Form_Element_Select_FromTable.
// Instead, it should be created right where it's used, or perhaps in ElementMaker.

class A25_Form_Element_Select_LicenseStatus extends A25_Form_Element_Select_FromTable
{
	public function __construct($name)
	{
		parent::__construct($name,'jos_license_status','status_id','status_name');
		$this->setLabel('Current License Status');
	}
}