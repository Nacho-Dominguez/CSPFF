<?php
require_once(dirname(__FILE__) . '/../../../autoload.php');

class test_unit_A25_PaymentProcessor extends
			test_Framework_UnitTestCase
{
	public function test_namePaymentTypeId()
	{
		$name = A25_PaymentProcessor::namePaymentTypeId(
				A25_Record_Pay::typeId_Cash);
		$this->assertEquals('A cash payment',$name);
		$name = A25_PaymentProcessor::namePaymentTypeId(
				A25_Record_Pay::typeId_Check);
		$this->assertEquals('A check',$name);
		$name = A25_PaymentProcessor::namePaymentTypeId(
				A25_Record_Pay::typeId_CreditCard);
		$this->assertEquals('A credit card payment',$name);
		$name = A25_PaymentProcessor::namePaymentTypeId(
				A25_Record_Pay::typeId_MoneyOrder);
		$this->assertEquals('A money order',$name);
		$name = A25_PaymentProcessor::namePaymentTypeId(
				A25_Record_Pay::typeId_ScholarshipCredit);
		$this->assertEquals('A scholarship',$name);
	}
}
?>
