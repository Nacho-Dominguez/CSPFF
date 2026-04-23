<?php

class A25_Form_Element_Text_Amount extends A25_Form_Element_Text
{
  public function __construct($name)
  {
    parent::__construct($name);
    $this->formatElement();
  }
  
  /**
   * @todo-jon-low-small - Don't set the error message here, instead do it in
   * the ElementMaker.  Maybe the validator should be moved into here from the
   * ElementMaker as well.
   */
  protected function formatElement()
  {
    $this->setLabel('Dollar amount')
        ->setAttrib('size', 5)
        ->setAttrib('maxlength', 7)
        ->setAttrib('placeholder', '0.00')
        ->setRequired(true)
        ->addErrorMessage(
            'Please enter a monetary amount of at least 1.00 such as 4.99');
  }
}
