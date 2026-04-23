<?php

class util_MassScholarshipCredit extends util_MassPayment
{
	private $credit_type_id;
	
	public function __construct($course_ids, $amountToPay, $credit_type_id)
	{
		$this->course_ids = $course_ids;
		$this->amountToPay = $amountToPay;
		$this->credit_type_id = $credit_type_id;
	}
	
	protected function payTypeId() {
		return A25_Record_Pay::typeId_ScholarshipCredit;
	}

	protected function applyCustom(A25_Record_Pay $pay)
	{
		$pay->associateWithScholarship($this->credit_type_id);
	}
}
