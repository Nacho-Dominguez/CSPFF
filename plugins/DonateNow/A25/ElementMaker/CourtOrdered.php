<?php

class A25_ElementMaker_CourtOrdered extends A25_StandardElementMaker
{
  protected function addSharedElements()
  {
    $name = new A25_Form_Element_Text('defendant');
    $name->setAttrib('size', 25)
        ->setRequired(true)
        ->addErrorMessage("Please enter the defendant's name");
		$this->elements[] = $name;

    $court = new A25_Form_Element_Select_FromTable('court_id', 'jos_court',
        'court_id', 'court_name', 'published = 1');
    $court->setLabel('Court')
        ->setRequired(true)
        ->addErrorMessage('Please select a court');
		$this->elements[] = $court;
    
    $reason = new Zend_Form_Element_Hidden('reason');
    $reason->setValue(A25_Record_IndependentDonation::reason_CourtOrder);
    $this->elements[] = $reason;
  }
}
