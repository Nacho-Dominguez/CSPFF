<?php

class test_unit_A25_Record_OrderItem_GetSubclassTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function ifTuitionReturnsTuition()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_CourseFee;
    
    $this->assertEquals('A25_Record_OrderItem_Tuition',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifLateFeeReturnsLateFee()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    
    $this->assertEquals('A25_Record_OrderItem_LateFee',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifReplaceCertFeeReturnsReplaceCertFee()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_ReplaceCertFee;
    
    $this->assertEquals('A25_Record_OrderItem_ReplaceCertFee',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifReturnCheckFeeReturnsReturnCheckFee()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_ReturnCheckFee;
    
    $this->assertEquals('A25_Record_OrderItem_ReturnCheckFee',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifNoShowFeeReturnsNoShowFee()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows;
    
    $this->assertEquals('A25_Record_OrderItem_NoShowFee',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifCreditCardFeeReturnsCreditCardFee()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_CreditCardFee;
    
    $this->assertEquals('A25_Record_OrderItem_CreditCardFee',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifCourtSurchargeReturnsCourtSurcharge()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    
    $this->assertEquals('A25_Record_OrderItem_CourtSurcharge',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifMoneyOrderDiscountReturnsMoneyOrderDiscount()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_MoneyOrderDiscount;
    
    $this->assertEquals('A25_Record_OrderItem_MoneyOrderDiscount',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
	/**
	 * @test
	 */
	public function ifExpiredPaymentReturnsExpiredPayment()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_ExpiredPayment;
    
    $this->assertEquals('A25_Record_OrderItem_ExpiredPayment',
        OrderItemWithGetSubclassExposed::getSubclass($fee->type_id));
	}
}

class OrderItemWithGetSubclassExposed extends A25_Record_OrderItem
{
  public static function getSubclass($type_id) {
    return parent::getSubclass($type_id);
  }
}