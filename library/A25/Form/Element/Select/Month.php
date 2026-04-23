<?php

class A25_Form_Element_Select_Month extends A25_Form_Element_Select
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->setMultiOptions($this->getMonths());
	}
	private function getMonths()
	{
    $num = 0;
    $array = array('Month');
    while($num < 12)
    {
      $num++;
      $array[] = str_pad($num, 2, '0', STR_PAD_LEFT);
    }
    return $array;
	}
}
?>
