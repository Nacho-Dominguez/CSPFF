<?php

class A25_Form_Record_HearAboutType extends A25_Form_Record
{
	public function __construct(A25_Record_HearAboutType $hearAboutType, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Hear About Type Saved';

        $hear_about_name = new A25_Form_Element_Text('hear_about_name');
        $hear_about_name->setRequired(true);
		$hear_about_name->setLabel('Description');
		$this->addElement($hear_about_name);

        $priority_id = new A25_Form_Element_Text('priority_id');
        $priority_id->setRequired(true);
		$priority_id->setLabel('Sort Order');
		$priority_id->setDescription('A number from 0-99. Lower-numbered items are listed first');
		$this->addElement($priority_id);
		
        parent::__construct($hearAboutType, $returnUrl, $isReadOnly);
    }

}
?>
