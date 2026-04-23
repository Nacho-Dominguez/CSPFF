<?php

/**
 * This class only allows for changing the amount.  Form_Record_OrderItem also
 * allows for changing the OrderItemType.
 */
class A25_Form_Record_OrderItemPrice extends A25_Form_Record
{
	public function __construct(A25_Record_OrderItem $item, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Line Item Updated';

        $amount = new A25_Form_Element_Text('unit_price');
        $amount->setRequired(true);
		$this->addElement($amount);
		
        parent::__construct($item, $returnUrl, $isReadOnly);
    }

	public function save()
	{
		parent::save();
    $this->_record->getStudent()->markAppropriateOrdersAndLineItemsAsPaid();

		return $this->saveAndReturnMessage();
	}
}