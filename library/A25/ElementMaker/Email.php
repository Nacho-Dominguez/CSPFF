<?php

class A25_ElementMaker_Email extends A25_StandardElementMaker
{
  private $address;
  
  public function __construct($address)
  {
    $this->address = $address;
  }
  public function addSharedElements()
  {
    $email = new A25_Form_Element_Text('x_email');
    $emailval = new Zend_Validate_EmailAddress();
    $email->setLabel('Receipt E-mail Address')
        ->setAttrib('size', 20)
        ->setValue($this->address)
        ->addValidator($emailval);
		$this->elements[] = $email;
  }
}
