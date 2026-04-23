<?php

class A25_Form_Record_CreditType extends A25_Form_Record
{
	public function __construct(A25_Record_CreditType $coupon, $returnUrl,
			$isReadOnly = false)
    {
		$this->successMessage = 'Credit Type Saved';

        $credit_type_name = new A25_Form_Element_Text('credit_type_name');
        $credit_type_name->setRequired(true);
		$this->addElement($credit_type_name);

        $total_value = new A25_Form_Element_Text('total_value');
        $total_value->setRequired(true);
		$this->addElement($total_value);
    
    self::fireCreditTypeFormField($this);

		$is_active = new A25_Form_Element_Radio_IsActive('is_active',
				A25_DI::DB());
        $is_active->setRequired(true);
		$this->addElement($is_active);
		
        parent::__construct($coupon, $returnUrl, $isReadOnly);
    }
	
	private static function fireCreditTypeFormField($form)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_CreditType) {
				$listener->creditTypeFormField($form);
			}
		}
	}
}
