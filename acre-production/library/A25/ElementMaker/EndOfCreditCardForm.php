<?php

class A25_ElementMaker_EndOfCreditCardForm extends A25_StandardElementMaker
{
  public function addSharedElements()
  {
    $validator = new Zend_Validate_Identical(1);
    $check = new A25_Form_Element_Checkbox('requirement_message');
    $check->setLabel('')
        ->addValidator($validator)
        ->addErrorMessage('Please acknowledge the Terms & Conditions');
		$this->elements[] = $check;
  }
}
