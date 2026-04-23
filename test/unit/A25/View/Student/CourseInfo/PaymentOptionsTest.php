<?php

class test_unit_A25_View_Student_CourseInfo_PaymentOptionsTest extends
    test_Framework_UnitTestCase
{
  private $course;
  private $config;
  
  public function setUp()
  {
    parent::setUp();
    
    $this->course = $this->getMock('A25_Record_Course', array('isPastPaymentOptionDeadline'));
    $this->config = A25_DI::PlatformConfig();
  }
  /**
   * @test
   */
  public function withinPaymentOptionDeadline()
  {
    $this->course->expects($this->any())->method('isPastPaymentOptionDeadline')->will($this->returnValue(true));
    $courseInfo = new CourseInfoWithPaymentOptionsExposed($this->course);
    
    $this->assertEquals('<div style="clear: both;"><p>Pay with Visa/Mastercard</p></div>', $courseInfo->paymentOptions());
  }
  /**
   * @test
   */
  public function outsidePaymentOptionDeadlineWithoutCheck()
  {
    $this->course->expects($this->any())->method('isPastPaymentOptionDeadline')->will($this->returnValue(false));
    $courseInfo = new CourseInfoWithPaymentOptionsExposed($this->course);
    $this->config->acceptChecks = false;
    
    $this->assertEquals('<div style="clear: both;"><p>Pay with Visa/Mastercard or money order</p></div>', $courseInfo->paymentOptions());
  }
  /**
   * @test
   */
  public function outsidePaymentOptionDeadlineWithCheck()
  {
    $this->course->expects($this->any())->method('isPastPaymentOptionDeadline')->will($this->returnValue(false));
    $courseInfo = new CourseInfoWithPaymentOptionsExposed($this->course);
    $this->config->acceptChecks = true;
    
    $this->assertEquals('<div style="clear: both;"><p>Pay with Visa/Mastercard, check, or money order</p></div>', $courseInfo->paymentOptions());
  }
}

class CourseInfoWithPaymentOptionsExposed extends A25_View_Student_CourseInfo
{
  public function paymentOptions()
  {
    return parent::paymentOptions();
  }
}
