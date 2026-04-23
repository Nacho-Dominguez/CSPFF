<?php

class util_MassCheck extends util_MassPayment
{
	private $paidBy;
	private $checkNumber;
	
	public function __construct($course_ids, $amountToPay, $paidBy, $checkNumber)
	{
		$this->course_ids = $course_ids;
		$this->amountToPay = $amountToPay;
		$this->paidBy = $paidBy;
		$this->checkNumber = $checkNumber;
	}

	protected function payTypeId() {
		return A25_Record_Pay::typeId_Check;
	}
	
	protected function applyCustom(A25_Record_Pay $pay)
	{
		$pay->paid_by_name = $this->paidBy;
		$pay->check_number = $this->checkNumber;
	}
}
