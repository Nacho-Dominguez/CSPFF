<?php

class A25_Form_Record_ReasonType extends A25_Form_Record
{
	public function __construct(A25_Record_ReasonType $reasonType, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Reason Type Saved';

        $reason_name = new A25_Form_Element_Text('reason_name');
        $reason_name->setRequired(true);
		$this->addElement($reason_name);

        $priority_id = new A25_Form_Element_Text('reason_key');
        $priority_id->setRequired(true);
		$this->addElement($priority_id);
		
        parent::__construct($reasonType, $returnUrl, $isReadOnly);
    }

}
?>
