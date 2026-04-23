<?php

class A25_ElementMaker_DonationBox extends A25_StandardElementMaker
{
  public function addSharedElements()
  {
    $radio = new A25_Form_Element_Radio('donationRadio');
		$radio->addMultiOptions(array('0'=>'No thank you','1'=>'$1','5'=>'$5','custom'=>'Another amount:'));
		$this->elements[] = $radio;
    
    $amount = new A25_Form_Element_Text_Amount('donateCustom_amount');
    $moneyval = new Zend_Validate_Regex('/^[1-9]\d{0,3}(\.\d{0,2})?$/');
    $amount->addValidator($moneyval)
        ->setRequired(false)
        ->setErrorMessages(array('Please enter a monetary amount such as 4.99'));
		$this->elements[] = $amount;
  }
}
