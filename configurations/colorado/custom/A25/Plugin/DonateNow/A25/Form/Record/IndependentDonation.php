<?php

class A25_Form_Record_IndependentDonation extends A25_Form_Record
{
  public function __construct($record)
  {
    return parent::__construct($record, null);
  }
  protected function redirect()
  {
    A25_DI::Redirector()->redirectBasedOnSiteRoot('/administrator/donation-receipt?id='
        . $this->_record->id);
  }
	protected function generateSaveButton()
	{
    // Do nothing
	}
}
