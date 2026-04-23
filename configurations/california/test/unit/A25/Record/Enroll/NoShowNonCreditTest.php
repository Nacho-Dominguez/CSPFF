<?php
require_once(dirname(__FILE__) . '/../../../../../../../autoload.php');

class california_NoShowNonCreditTest_Enroll
		extends A25_Record_Enroll
{
	public function isPaid()
	{	
	}
	public function NoShowNonCredit()
	{
		parent::NoShowNonCredit();
	}
}
class california_NoShowNonCreditTest_Order extends A25_Record_Order
{
	public $wasCalled = false;
	public function A25_AddFeesWhenMarkingAsNoShow()
	{
		$this->wasCalled = true;
	}
}
class california_NoShowNonCreditTest_PaidEnroll
		extends california_NoShowNonCreditTest_Enroll
{
	public function isPaid()
	{
		return true;
	}
}
class california_NoShowNonCreditTest_UnpaidEnroll
		extends california_NoShowNonCreditTest_Enroll
{
	public function isPaid()
	{
		return false;
	}
}

class configurations_california_test_unit_A25_Record_Enroll_NoShowNonCreditTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function changesItemTypeToNonRefundable_ifPaid()
	{
		$enroll = new california_NoShowNonCreditTest_PaidEnroll();
    $enroll->Order = new california_NoShowNonCreditTest_Order;

		$enroll->NoShowNonCredit();

		$this->assertEquals(true, $enroll->Order->wasCalled);

	}
	/**
	 * @test
	 */
	public function doesNotItemTypeToNonRefundable_ifNotPaid()
	{
		$enroll = new california_NoShowNonCreditTest_UnpaidEnroll();
    $enroll->Order = new california_NoShowNonCreditTest_Order;

		$enroll->NoShowNonCredit();

		$this->assertEquals(false, $enroll->Order->wasCalled);
	}
}
