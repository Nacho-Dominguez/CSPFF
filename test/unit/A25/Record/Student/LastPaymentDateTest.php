<?php

class test_unit_A25_Record_Student_LastPaymentDateTest extends
    test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function selectsLatestDateFrom2Payments_whereDayIsLater()
	{
    $student = new A25_Record_Student();
    
    $pay1 = new A25_Record_Pay();
    $pay1->created = '2012-01-01';
    $pay1->Student = $student;
    
    $pay2 = new A25_Record_Pay();
    $pay2->created = '2012-01-02';
    $pay2->Student = $student;
    
    $this->assertEquals($pay2->created, $student->lastPaymentDate());
	}
  
	/**
	 * @test
	 */
	public function selectsLatestDateFrom2Payments_whereMonthIsLater()
	{
    $student = new A25_Record_Student();
    
    $pay1 = new A25_Record_Pay();
    $pay1->created = '2012-01-02';
    $pay1->Student = $student;
    
    $pay2 = new A25_Record_Pay();
    $pay2->created = '2012-02-01';
    $pay2->Student = $student;
    
    $this->assertEquals($pay2->created, $student->lastPaymentDate());
	}
  
	/**
	 * @test
	 */
	public function selectsLatestDateFrom2Payments_whereYearIsLater()
	{
    $student = new A25_Record_Student();
    
    $pay1 = new A25_Record_Pay();
    $pay1->created = '2012-02-02';
    $pay1->Student = $student;
    
    $pay2 = new A25_Record_Pay();
    $pay2->created = '2013-01-01';
    $pay2->Student = $student;
    
    $this->assertEquals($pay2->created, $student->lastPaymentDate());
	}
  
  /**
   * @test 
   */
  public function whenNoPayments_returnsNull()
  {
    $student = new A25_Record_Student();
    $this->assertNull($student->lastPaymentDate());
  }
 
  /**
   * @test 
   */
  public function choosesAnyDateOverNullCreation()
  {
    $student = new A25_Record_Student();
    
    $pay1 = new A25_Record_Pay();
    $pay1->created = null;
    $pay1->Student = $student;
    
    $pay2 = new A25_Record_Pay();
    $pay2->created = '2013-01-01';
    $pay2->Student = $student;
    
    $this->assertEquals($pay2->created, $student->lastPaymentDate());
  }
}