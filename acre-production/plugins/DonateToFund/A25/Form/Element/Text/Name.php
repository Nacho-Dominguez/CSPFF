<?php

class A25_Form_Element_Text_Name extends A25_Form_Element_Text
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->formatElement();
  }
  
  protected function formatElement()
  {
    $this->setLabel('Name of giver')
        ->setAttrib('size', 25)
        ->setAttrib('maxlength', 70)
        ->setRequired(true)
        ->addErrorMessage('Please enter your name');
  }
}
