<?php

class A25_ElementMaker_DonationAmount extends A25_StandardElementMaker
{
  public function addSharedElements()
  {
    $amount = new A25_Form_Element_Hidden('donation_amount');
    $amount->setLabel('')
        ->setValue(0);
		$this->elements[] = $amount;
  }
}
