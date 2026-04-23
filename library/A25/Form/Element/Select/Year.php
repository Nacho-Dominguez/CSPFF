<?php

class A25_Form_Element_Select_Year extends A25_Form_Element_Select
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setMultiOptions($this->getYears());
	}
	private function getYears()
	{
    $num = 0;
    $year = date("Y");
    $array = array('Year');
    while($num < 20)
    {
      $num++;
      $array[$year] = $year;
      $year++;
    }
    return $array;
	}
}
?>
