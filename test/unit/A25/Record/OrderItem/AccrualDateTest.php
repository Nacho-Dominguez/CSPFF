<?php
class test_unit_A25_Record_OrderItem_AccrualDateTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function lateFee_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_LateFee();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function replacementCertificate_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_ReplaceCertFee();
    $fee->type_id = A25_Record_OrderItemType::typeId_ReplaceCertFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function returnCheckFee_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_ReturnCheckFee();
    $fee->type_id = A25_Record_OrderItemType::typeId_ReturnCheckFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function creditCardFee_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_CreditCardFee();
    $fee->type_id = A25_Record_OrderItemType::typeId_CreditCardFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function courtSurcharge_shouldReturnNothing()
	{
    $fee = new A25_Record_OrderItem_CourtSurcharge();
    $fee->type_id = A25_Record_OrderItemType::typeId_CourtSurcharge;
    $fee->created = '2012-01-01 08:00:00';
    $fee->date_paid = '2012-01-01';
    
    $this->assertNull($fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function noShowFee_shouldReturnDateOfCourse()
	{
    $fee = $this->getMock('A25_Record_OrderItem_NoShowFee',
        array('courseDatetime','isActive'));
    
    $this->setToActiveWithRandomCourseDate($fee);
    
    $fee->type_id = A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals(OrderItem4AccrualDateTest::COURSE_DATE,
            $fee->accrualDate());
	}
  
  /**
	 * @test
	 */
	public function courseFee_whenAttended_shouldReturnDateOfCourse()
	{
    $fee = $this->getMock('A25_Record_OrderItem_Tuition',
        array('courseDatetime','isActive','wasAttended'));
    
    $this->setToActiveWithRandomCourseDate($fee);
    $fee->expects($this->any())->method('wasAttended')
        ->will($this->returnValue(true));
    
    $fee->type_id = A25_Record_OrderItemType::typeId_CourseFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals(OrderItem4AccrualDateTest::COURSE_DATE,
            $fee->accrualDate());
	}
  
  /**
	 * @test
	 */
	public function courseFee_whenNotAttended_shouldReturnNothing()
	{
    $fee = $this->getMock('A25_Record_OrderItem_Tuition',
        array('courseDatetime','isActive','wasAttended'));
    
    $this->setToActiveWithRandomCourseDate($fee);
    $fee->expects($this->any())->method('wasAttended')
        ->will($this->returnValue(false));
    
    $fee->type_id = A25_Record_OrderItemType::typeId_CourseFee;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertNull($fee->accrualDate());
	}
  
  /**
	 * @test
	 */
	public function waivedFee_shouldReturnNothing()
	{
    $fee = new A25_Record_OrderItem();
    $fee->type_id = A25_Record_OrderItemType::typeId_LateFee;
    $fee->created = '2012-01-01 08:00:00';
    $fee->waive();
    
    $this->assertNull($fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function expiredPayment_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_ExpiredPayment();
    $fee->type_id = A25_Record_OrderItemType::typeId_ExpiredPayment;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
	/**
	 * @test
	 */
	public function moneyOrderDiscount_shouldReturnDateCreated()
	{
    $fee = new A25_Record_OrderItem_MoneyOrderDiscount();
    $fee->type_id = A25_Record_OrderItemType::typeId_MoneyOrderDiscount;
    $fee->created = '2012-01-01 08:00:00';
    
    $this->assertEquals('2012-01-01', $fee->accrualDate());
	}
  
  private function setToActiveWithRandomCourseDate($fee)
  {
    $fee->expects($this->any())->method('courseDatetime')
        ->will($this->returnValue(
            OrderItem4AccrualDateTest::COURSE_DATE . ' 09:00:00'));
    
    $fee->expects($this->any())->method('isActive')
        ->will($this->returnValue(true));
  }
}

class OrderItem4AccrualDateTest extends A25_Record_OrderItem
{
  const COURSE_DATE = '2012-04-27';
  
  public function courseDatetime()
  {
    return OrderItem4AccrualDateTest::COURSE_DATE . ' 09:00:00';
  }
  
  public function isActive()
  {
    return true;
  }
}

class OrderItemWhenAttended extends OrderItem4AccrualDateTest
{
  public function wasAttended()
  {
    return true;
  }
}

class OrderItemNotAttended extends OrderItem4AccrualDateTest
{
  public function wasAttended()
  {
    return false;
  }
}