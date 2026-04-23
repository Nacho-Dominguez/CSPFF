<?php

// This plugin is still in development and has not been activated.
class A25_Form_RequestSupplies extends A25_Form
{
  public $successMessage = 'Successfully Sent Supply Request';
  
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
    
    $date = new A25_Form_Element_Text('request_date');
    $date->setReadOnly(true);
    $date->setValue(date('l F j, Y \a\t g:i a'));
    $this->addElement($date);
    
    $address_1 = new A25_Form_Element_Text('address_1');
    $address_1->setAttrib('size', 30);
    $address_1->setRequired(true);
		$this->addElement($address_1);

    $address_2 = new A25_Form_Element_Text('address_2');
    $address_2->setAttrib('size', 30);
    $address_2->setRequired(false);
		$this->addElement($address_2);

    $city = new A25_Form_Element_Text('city');
    $city->setAttrib('size', 30);
    $city->setRequired(true);
		$this->addElement($city);

    $zip = new A25_Form_Element_Text('zip');
    $zip->setAttrib('size', 4);
    $zip->setRequired(true);
		$this->addElement($zip);

    $phone = new A25_Form_Element_Text('phone');
    $phone->setAttrib('size', 13);
    $phone->setRequired(false);
		$this->addElement($phone);

    $quantity = new A25_Form_Element_Text('quantity_requested');
    $quantity->setAttrib('size', 2);
    $quantity->setRequired(true);
		$this->addElement($quantity);

    $supplies = new A25_Form_Element_Textarea('supplies_needed');
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
    $address = ServerConfig::supplyRequestRecipientEmailAddress();
    $subject = "Alive At 25: Instructor Supply Request";
    $body = "Quantity Requested:\n"
        . $this->getElement('quantity_requested')->getValue()
        . "\n\nSupplies Requested:\n"
        . $this->getElement('supplies_needed')->getValue()
        . "\n\nInstructor Name: "
        . A25_DI::User()->name
        . "\nInstructor E-mail: "
        . A25_DI::User()->email
        . "\nDeliver To Address:\n"
        . $this->getElement('address_1')->getValue() . " "
        . $this->getElement('address_2')->getValue() . "\n"
        . $this->getElement('city')->getValue() . ", "
        . PlatformConfig::STATE_NAME . " "
        . $this->getElement('zip')->getValue() . "\n"
        . "Phone: "
        . $this->getElement('phone')->getValue();
    A25_DI::Mailer()->mail($address, $subject, $body, 0);
    return $this->successMessage;
	}
}
