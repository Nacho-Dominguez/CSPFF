<?php

class A25_Form_Record_Enroll extends A25_Form_Record
{
	public function __construct(A25_Record_Enroll $enroll, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Enrollment Updated';

        $status_id = new A25_Form_Element_Select_FromTable('status_id',
				'jos_enroll_status','status_id','status_name');
        $status_id->setRequired(true)
				->setLabel('Status');
		$this->addElement($status_id);

        $hear_about_id = new A25_Form_Element_Select_FromTable('hear_about_id',
				'jos_hear_about_type','hear_about_id','hear_about_name');
        $hear_about_id->setRequired(true)
				->setLabel('How they heard');
		$this->addElement($hear_about_id);

        $reason_id = new A25_Form_Element_Select_FromTable('reason_id',
				'jos_reason_type','reason_id','reason_name');
        $reason_id->setRequired(true)
				->setLabel('Reason');
		$this->addElement($reason_id);

        $court_id = new A25_Form_Element_Select_FromTable('court_id',
				'jos_court','court_id','court_name');
        $court_id->setLabel('Court');
		$this->addElement($court_id);

		$is_late = new A25_Form_Element_Radio('is_late');
        $is_late->setRequired(true)
				->setLabel('Late Enrollment?')
				->addMultiOptions(array('1'=>'Yes','0'=>'No'));
		$this->addElement($is_late);
    
    $this->fireAfterIsLate();
		
        parent::__construct($enroll, $returnUrl, $isReadOnly);
    }
    
	public function save()
	{
    $this->_record->kick_out_date = null;
		parent::save();
    $this->_record->Student->markAppropriateOrdersAndLineItemsAsPaid();

		return $this->saveAndReturnMessage();
	}

  private function fireAfterIsLate()
  {
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_AdminEnroll)
		  {
			  $listener->afterIsLateEdit($this);
		  }
	  }
  }
}