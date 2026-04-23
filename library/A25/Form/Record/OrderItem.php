<?php

class A25_Form_Record_OrderItem extends A25_Form_Record
{	
	public function __construct(A25_Record_OrderItem $item, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Added fee to order';

        $amount = new A25_Form_Element_Select_FromTable('type_id',
				'jos_order_item_type', 'type_id', 'type_name');
        $amount->setRequired();
		$this->addElement($amount);
		
        $amount = new A25_Form_Element_Text('unit_price');
        $amount->setRequired();
		$this->addElement($amount);
		
		$order = new Zend_Form_Element_Hidden('order_id');
		$order->setValue($item->order_id);
		$this->addElement($order);
		
        parent::__construct($item, $returnUrl, $isReadOnly);
    }
}