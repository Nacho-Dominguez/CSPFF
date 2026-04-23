<?php
class A25_Form_Record_Refund extends A25_Form_Record
{

	public function __construct(A25_Record_Pay $pay, $returnUrl,
			$isReadOnly=false)
    {
		$this->successMessage = 'Refund Saved';

		$enrollment = new A25_Form_Element_Select_StudentEnrollments('xref_id', $pay->Student);
		$enrollment->setRequired(true)
				->setLabel('Enrollment')
				->setValue($pay->Student->getActiveEnrollment()->xref_id);
		$this->addElement($enrollment);
		
        $paid_to = new A25_Form_Element_Text('paid_by_name');
        $paid_to->setRequired(true)
				->setLabel('Paid To');
		$this->addElement($paid_to);

		$refund_type_id = new A25_Form_Element_Select_FromTable('refund_type_id',
				'jos_order_item_type','type_id','type_name');
        $refund_type_id->setRequired(true)
				->setLabel('For');
		$this->addElement($refund_type_id);

		$pay_type_id = new A25_Form_Element_Select('pay_type_id');
		$payment_methods[A25_Record_Pay::typeId_Cash] = 'Cash';
		$payment_methods[A25_Record_Pay::typeId_Check] = 'Check';
		$payment_methods[A25_Record_Pay::typeId_CreditCard] = 'Credit Card';
		$pay_type_id->addMultiOptions($payment_methods);
        $pay_type_id->setRequired(true)
				->setLabel('Payment Method');
		$this->addElement($pay_type_id);

        $amount = new A25_Form_Element_Text('amount');
        $amount->setRequired(true);
		$this->addElement($amount);

		$note = new A25_Form_Element_Text('notes');
		$this->addElement($note);

        $dateOriginallyCollected = new A25_Form_Element_Date('refund_date_originally_collected');
        $dateOriginallyCollected->setRequired(true);
		$this->addElement($dateOriginallyCollected);
		
		parent::__construct($pay, $returnUrl, $isReadOnly);
    }

	protected function save()
	{
		$amount = $this->getElement('amount');
		$amount->setValue(-$amount->getValue());

		$enroll = A25_Record_Enroll::retrieve(
				$this->getElement('xref_id')->getValue());

		$this->_record->assignEnrollment($enroll);
		
		return parent::save();
	}
}
?>
