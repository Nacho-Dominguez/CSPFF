<?php

class A25_Form_Element_Select_LocationParent extends A25_Form_Element_Select
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setLabel('Location')
			 ->addMultiOptions($this->locations());
	}
	private function locations()
	{
		$locations = A25_Record_LocationParent::retrieveAllAvailable();
		return A25_Form_Record::createSelectionList($locations);
	}
}
?>
