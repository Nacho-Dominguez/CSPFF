<?php

class A25_Form_Record_Agency extends A25_Form_Record
{
	public function __construct(A25_Record_Agency $agency)
  {
		$this->successMessage = 'Agency Saved';
    
		$name = new A25_Form_Element_Text('name');
        $name->setRequired(true);
        $name->setLabel('Agency Name');
        $name->setAttrib('autofocus', 'autofocus');
		$this->addElement($name);
		
    parent::__construct($agency, null);
  }
  
  protected function redirect()
  {
    A25_DI::Redirector()->redirectBasedOnSiteRoot('/administrator/ViewAgencies',
        $this->successMessage);
  }
}