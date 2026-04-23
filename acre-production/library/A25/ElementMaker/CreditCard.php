<?php

class A25_ElementMaker_CreditCard extends A25_StandardElementMaker
{
  private $month;
  
  private $year;
  
  public function addSharedElements()
  {
    $number = new A25_Form_Element_Text('card_number');
    $number->setLabel('Card number')
        ->setAttrib('size', 20)
        ->setAttrib('maxlength', 25)
        ->setAttrib('autocomplete', 'off')
        ->setRequired(true)
        ->setDescription('<img style="vertical-align: bottom; margin-left: 6px;" src="' . A25_Link::to('images/visa_mastercard.png') . '" height="31" />')
        ->addErrorMessage('Please enter a valid credit or debit card number');
    $numval = new Zend_Validate_Ccnum();
    $number->addValidator($numval);
		$this->elements[] = $number;
    
    $this->month = new A25_Form_Element_Select_Month('expiration_month');
    $this->month->setLabel('Expiration')
        ->setRequired(true)
        ->addFilter('Int')
        ->setDescription('/')
        ->addErrorMessage('Please select an expiration date');
		$this->elements[] = $this->month;
    
    $this->year = new A25_Form_Element_Select_Year('expiration_year');
    $this->year->setRequired(true)
        ->addFilter('Int')
        ->addErrorMessage('Please select an expiration date');
		$this->elements[] = $this->year;
    
    $cvv = new A25_Form_Element_Text('cvv_number');
    $cvv->setLabel('Security code')
        ->setAttrib('size', 3)
        ->setAttrib('maxlength', 4)
        ->setAttrib('autocomplete', 'off')
        ->setRequired(true)
        ->setDescription('<a href="javascript:void()" title="The CVV number">What\'s this?</a>')
        ->addErrorMessage('Please enter a valid security code');
    $cvvlength = new Zend_Validate_StringLength(3, 4);
    $cvvdigits = new Zend_Validate_Digits();
    $cvv->addValidator($cvvlength, true);
    $cvv->addValidator($cvvdigits, true);
		$this->elements[] = $cvv;
  }
  
  protected function decorateAllElements()
  {
    parent::decorateAllElements();
    
    $this->month->getDecorator('content')->setOption('openOnly', true);
    $this->year->removeDecorator('Label');
    $this->year->getDecorator('content')->setOption('closeOnly', true);
    
    $head = A25_DI::HtmlHead();
    $head->includeJqueryUI();
    $head->append('
      <script type="text/javascript">
      $(function() {
          $( document ).tooltip({
            track: true,
            tooltipClass: "tooltip",
            content: function() {
              return "<img src=\'' . A25_Link::to('/images/cvvcode.gif') . '\' />";
            }
          });
      });
      </script>
    ');
  }
}
