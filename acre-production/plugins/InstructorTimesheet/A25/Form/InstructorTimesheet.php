<?php

// This plugin is still in development and has not been activated.
class A25_Form_InstructorTimesheet extends A25_Form
{
  public $successMessage = 'Successfully Sent Timesheet';
  
  public function __construct()
  {
    $user = A25_DI::User();
    
    $instructor = new A25_Form_Element_Text('instructor');
    $instructor->setReadOnly(true);
    $instructor->setValue($user->name);
    $this->addElement($instructor);
    
    $email = new A25_Form_Element_Text('email_address');
    $email->setReadOnly(true);
    $email->setValue($user->email);
    $this->addElement($email);
    
    $request_date = new A25_Form_Element_Text('request_date');
    $request_date->setReadOnly(true);
    $request_date->setValue(date('l F j, Y \a\t g:i a'));
    $this->addElement($request_date);
    
    $address_1 = new A25_Form_Element_Text('address_1');
    $address_1->setReadOnly(true);
    $address_1->setValue($user->address_1);
		$this->addElement($address_1);

    $address_2 = new A25_Form_Element_Text('address_2');
    $address_2->setReadOnly(true);
    $address_2->setValue($user->address_2);
		$this->addElement($address_2);

    $city = new A25_Form_Element_Text('city');
    $city->setReadOnly(true);
    $city->setValue($user->city);
		$this->addElement($city);

    $zip = new A25_Form_Element_Text('zip');
    $zip->setReadOnly(true);
    $zip->setValue($user->zip);
		$this->addElement($zip);

    $phone = new A25_Form_Element_Text('work_phone');
    $phone->setReadOnly(true);
    $phone->setValue($user->work_phone);
		$this->addElement($phone);

    $date = new A25_Form_Element_Text('date');
    $date->setAttrib('size', 30);
    $date->setRequired(true);
		$this->addElement($date);

    $time = new A25_Form_Element_Text('time');
    $time->setAttrib('size', 30);
    $time->setRequired(true);
		$this->addElement($time);

    $time_spent = new A25_Form_Element_Text('time_spent');
    $time_spent->setAttrib('size', 30);
    $time_spent->setRequired(true);
		$this->addElement($time_spent);

    $supplies = new A25_Form_Element_Textarea('description');
    $supplies->setRequired(true);
		$this->addElement($supplies);
    
    parent::__construct(null);
  }
  
// This plugin is still in development and has not been activated.
  protected function redirect() {
    A25_DI::Redirector()->redirectBasedOnSiteRoot('/administrator/index2.php', 
        $this->successMessage);
  }
  
// This plugin is still in development and has not been activated.
	protected function save()
	{
    $address = ServerConfig::timesheetRecipientEmailAddress();
    $subject = "Alive At 25: Instructor Timesheet For Marketing";
    $body = "Instructor Timesheet For Marketing:\nDate: "
        . $this->getElement('date')->getValue()
        . "\nTime: "
        . $this->getElement('time')->getValue()
        . "\nTime Spent: "
        . $this->getElement('time_spent')->getValue()
        . "\nLocation/Description: "
        . $this->getElement('description')->getValue()
        . "\n\nFrom Instructor:\nInstructor Name: "
        . A25_DI::User()->name
        . "\nInstructor Email: "
        . A25_DI::User()->email
        . "\nAddress:\n"
        . A25_DI::User()->address_1 . " "
        . A25_DI::User()->address_2 . "\n"
        . A25_DI::User()->city . ", "
        . PlatformConfig::STATE_NAME . " "
        . A25_DI::User()->zip
        . "\nWork Phone: "
        . A25_DI::User()->work_phone;
    A25_DI::Mailer()->mail($address, $subject, $body, 0);
    return $this->successMessage;
	}
}
