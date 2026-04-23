<?php

class A25_Form_Element_Select_Instructor extends A25_Form_Element_Select
{
	public function __construct($name, $location)
	{
		parent::__construct($name);
		$this->addMultiOptions($this->getInstructors($location));
	}
	private function getInstructors($location)
	{
		$selections = array();
		
		if ($location) {
			$instructors = $location->getUsers();

			$selections["0"] = "--Select Instructor--";

			if ($instructors)
				foreach ($instructors as $instructor) {
					$selections[$instructor->id] = $instructor->name;
				}
		} else {
			$selections["0"] = "--Available After Selecting Location--";
		}

		return $selections;
	}
  public function setReadOnly()
  {
    $this->removeMultiOption('0');
    return parent::setReadOnly();
  }
}