<?php

class A25_Form_Record_StudentAddressVerification extends A25_Form_Record
{
	public function __construct(A25_Record_Student $student, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Information Updated';

        $address_1 = new A25_Form_Element_Text('address_1');
        $address_1->setRequired(true);
		$this->addElement($address_1);

        $address_2 = new A25_Form_Element_Text('address_2');
        $address_2->setRequired(false);
		$this->addElement($address_2);

        $city = new A25_Form_Element_Text('city');
        $city->setRequired(true);
		$this->addElement($city);

		$state = new A25_Form_Element_Select_State('state');
        $state->setRequired(true);
		$this->addElement($state);

        $zip = new A25_Form_Element_Text('zip');
        $zip->setRequired(true);
		$this->addElement($zip);

        $home_phone = new A25_Form_Element_Text('home_phone');
        $home_phone->setRequired(true)
				->setLabel('Primary Phone');
		$this->addElement($home_phone);
		
        parent::__construct($student, $returnUrl, $isReadOnly);
    }
}