<?php

class A25_ElementMaker_PaymentType extends A25_StandardElementMaker
{
  public function addSharedElements()
  {
    $type = new A25_Form_Element_Select_FromTable('pay_type_id', 'jos_pay_type',
        'pay_type_id', 'pay_type_name');
    $type->setLabel('Payment Type')
        ->setRequired(true);
    $this->elements[] = $type;
  }
  
  protected function decorateAllElements()
  {
    parent::decorateAllElements();
  }
}
