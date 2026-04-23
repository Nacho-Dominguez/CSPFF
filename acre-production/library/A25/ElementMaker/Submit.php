<?php

class A25_ElementMaker_Submit extends A25_StandardElementMaker
{
  private $submit;
  
  public function addSharedElements()
  {
    $this->submit = new Zend_Form_Element_Submit('submit');
		$this->submit->setLabel('Continue');
		$this->elements[] = $this->submit;
  }
  
  protected function decorateAllElements()
  {
    parent::decorateAllElements();
    
    $this->submit->removeDecorator('Label');
  }
}
