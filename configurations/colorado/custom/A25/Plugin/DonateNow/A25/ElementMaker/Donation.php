<?php

class A25_ElementMaker_Donation extends A25_StandardElementMaker
{
  protected function addSharedElements()
  {
    $amount = new A25_Form_Element_Text_Amount('x_amount');
    $moneyval = new Zend_Validate_Regex('/^[1-9]\d{0,3}(\.\d{0,2})?$/');
    $amount->addValidator($moneyval);
		$this->elements[] = $amount;

    $first = new A25_Form_Element_Text_Name('benefactor');
		$this->elements[] = $first;
  }
}
