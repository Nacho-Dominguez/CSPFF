<?php

class A25_ElementMaker_Payment extends A25_StandardElementMaker
{
  private $amount;
  public function __construct($amount, $order)
  {
    $this->amount = $amount;
    $this->order = $order;
  }
  public function addSharedElements()
  {
    $first = new A25_Form_Element_Text('x_first_name');
    $first->setLabel('First Name On Card')
        ->setAttrib('size', 20)
        ->setAttrib('maxlength', 50)
        ->setRequired(true)
        ->addErrorMessage('Please enter the cardholder\'s first name');
		$this->elements[] = $first;
    
    $last = new A25_Form_Element_Text('x_last_name');
    $last->setLabel('Last Name On Card')
        ->setAttrib('size', 20)
        ->setAttrib('maxlength', 50)
        ->setRequired(true)
        ->addErrorMessage('Please enter the cardholder\'s last name');
		$this->elements[] = $last;
    
    $address = new A25_Form_Element_Text('x_address');
    $address->setLabel('Billing Address')
        ->setAttrib('size', 20)
        ->setAttrib('maxlength', 60)
        ->setRequired(true)
        ->addErrorMessage('Please enter the billing address');
		$this->elements[] = $address;
    
    $city = new A25_Form_Element_Text('x_city');
    $city->setLabel('City')
        ->setAttrib('size', 20)
        ->setAttrib('maxlength', 50)
        ->setRequired(true)
        ->addErrorMessage('Please enter the city for the billing address');
		$this->elements[] = $city;
    
    $state = new A25_Form_Element_Select_State('x_state');
    $state->setRequired(true)
        ->addErrorMessage('Please enter the state for the billing address');
		$this->elements[] = $state;
    
    $zip = new A25_Form_Element_Text('x_zip');
    $zip->setLabel('Zip')
        ->setAttrib('size', 5)
        ->setAttrib('maxlength', 5)
        ->setRequired(true)
        ->addErrorMessage('Please enter the zip code for the billing address (5 digits only)');
		$this->elements[] = $zip;
    
    $payment = new A25_Form_Element_Hidden('x_amount');
    $payment->setLabel('')
        ->setValue($this->amount);
		$this->elements[] = $payment;
    
    $order = new A25_Form_Element_Hidden('order_id');
    $order->setLabel('')
        ->setValue($this->order);
    $this->elements[] = $order;
  }
}
