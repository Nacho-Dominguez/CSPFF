<?php

class A25_Form_Record_Register extends A25_Form_Record
{
	public function __construct()
  {
		$this->successMessage = 'Success message goes here';
    
    $student = new A25_Record_Student();
    $student->userid = 'testuser';
    
		$first_name = new A25_Form_Element_Text('first_name');
    $first_name->setAttrib('size', 30)
        ->setAttrib('maxlength', 25)
        ->setRequired(true);
		$this->addElement($first_name);

		$middle = new A25_Form_Element_Text('middle_initial');
    $middle->setAttrib('size', 1)
        ->setAttrib('maxlength', 1);
		$this->addElement($middle);

		$last_name = new A25_Form_Element_Text('last_name');
    $last_name->setAttrib('size', 30)
        ->setAttrib('maxlength', 25)
        ->setRequired(true);
		$this->addElement($last_name);

		$address_1 = new A25_Form_Element_Text('address_1');
    $address_1->setLabel('Mailing Address 1')
        ->setRequired(true);
		$this->addElement($address_1);

		$address_2 = new A25_Form_Element_Text('address_2');
    $address_2->setLabel('Mailing Address 2');
		$this->addElement($address_2);

		$city = new A25_Form_Element_Text('city');
    $city->setRequired(true);
		$this->addElement($city);
    
    $state = new A25_Form_Element_Select_State('state');
    $state->setRequired(true);
    $this->addElement($state);

		$zip = new A25_Form_Element_Text('zip');
    $zip->setLabel('Zip Code')
        ->setAttrib('size', 10)
        ->setAttrib('maxlength', 10)
        ->setRequired(true);
		$this->addElement($zip);

		$email = new A25_Form_Element_Text('email');
    $email->setAttrib('size', 30)
        ->setAttrib('maxlength', 60);
		$this->addElement($email);

		$phone_1 = new A25_Form_Element_Text('home_phone');
    $phone_1->setRequired(true);
		$this->addElement($phone_1);
    
    $this->fireRegistrationFormAfterEachPhone('home_sms');

		$phone_2 = new A25_Form_Element_Text('work_phone');
		$this->addElement($phone_2);
    
    $this->fireRegistrationFormAfterEachPhone('work_sms');
    
    $license = new A25_Form_Element_Select_LicenseStatus('license_status');
    $license->setRequired(true);
    $this->addElement($license);
    
    $license_state = new A25_Form_Element_Select_State('license_state');
    $license_state->setLabel('License Issuing State')
        ->setRequired(true);
    $this->addElement($license_state);
    
    $this->fireAfterLicenseIssuingState();
    
    $gender = new A25_Form_Element_Radio_Gender('gender');
    $gender->setRequired(true);
    $this->addElement($gender);
    
    $special = new A25_Form_Element_Textarea('special_needs');
    $special->setLabel('Please specify any special physical or learning needs')
        ->setAttrib('rows', 5)
        ->setAttrib('cols', 40)
        ->setAttrib('maxlength', 255);
    $this->addElement($special);
		
    parent::__construct($student, $redirectToQuerystring);
  }

	private function fireAfterLicenseIssuingState()
	{
		foreach (A25_ListenerManager::all() as $listener)
		{
			if ($listener instanceof A25_ListenerI_LicenseInfo)
			{
				$listener->afterLicenseIssuingState($this);
			}
		}
	}

	private function fireRegistrationFormAfterEachPhone($name)
	{
		foreach (A25_ListenerManager::all() as $listener)
		{
			if ($listener instanceof A25_ListenerI_PhoneNumbers)
			{
				$listener->registrationFormAfterEachPhone($this, $name);
			}
		}
	}
}