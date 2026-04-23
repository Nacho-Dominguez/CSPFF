<?php

class A25_ElementMaker_LicensePlate extends A25_StandardElementMaker
{
    /**
      * @todo - Separate benefactor element, so that other forms that handle
      * benefactor differently, such as A25_SimLicensePlateDonationForm, can use
      * this class, instead of duplicating all its other fields.
     */
  protected function addSharedElements()
  {
    $amount = new A25_Form_Element_Text_Amount('x_amount');
    $amount->setAttrib('placeholder', '30.00')
        ->setErrorMessages(array('Please enter a monetary amount of at least 30.00'));
    $moneyval = new Zend_Validate_Regex('/^([3-9]\d|[1-9]\d{2,3})(\.\d{0,2})?$/');
    $amount->addValidator($moneyval);
		$this->elements[] = $amount;

    $first = new A25_Form_Element_Text_Name('benefactor');
		$this->elements[] = $first;

    $reason = new Zend_Form_Element_Hidden('reason');
    $reason->setValue(A25_Record_IndependentDonation::reason_LicensePlate);
    $this->elements[] = $reason;
  }
}
