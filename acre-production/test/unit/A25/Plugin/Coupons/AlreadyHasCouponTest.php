<?php

require_once ServerConfig::webRoot . '/plugins/Coupons.php';

class test_unit_A25_Plugin_Coupons_AlreadyHasCouponTest extends
		test_Framework_UnitTestCase
{
  private $enroll;
  private $prev;
  private $coupon;
  private $oldCoupon;
  private $plugin;
  public function setUp()
  {
    parent::setUp();
    
    $this->enroll = $this->getMock('A25_Record_Enroll');
    $student = new A25_Record_Student();
    $this->enroll->Student = $student;
    
    $this->prev = $this->getMock('A25_Record_Enroll', array('previousEnrollment'));
    $this->prev->Student = $student;
    $this->prev->xref_id = '456';
    $this->prev->status_id = A25_Record_Enroll::statusId_canceled;
    $this->enroll->expects($this->any())->method('previousEnrollment')
        ->will($this->returnValue($this->prev));
    
    $this->coupon = new A25_Record_CreditType();
    $this->coupon->credit_type_id = '123';
    $this->oldCoupon = new A25_Record_Credit();
    $this->oldCoupon->credit_type_id = $this->coupon->credit_type_id;
    $this->oldCoupon->xref_id = $this->prev->xref_id;
    
    $this->plugin = $this->getMock('CouponWithMethodsExposed',
        array('existingCredits'));
  }
  /**
   * @test
   */
  public function falseIfNoExistingCoupons()
  {
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(null));
    $this->assertFalse($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
  /**
   * @test
   */
  public function falseIfCouponOnCompletedEnrollment()
  {
    $this->prev->status_id = A25_Record_Enroll::statusId_completed;
    
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(array($this->oldCoupon)));
    $this->assertFalse($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
  /**
   * @test
   */
  public function falseIfNotSameCreditType()
  {
    $this->oldCoupon->credit_type_id = '567';
    
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(array($this->oldCoupon)));
    $this->assertFalse($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
  /**
   * @test
   */
  public function trueIfCouponButNoPreviousEnrollments()
  {
    $this->prev = null;
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(array($this->oldCoupon)));
    $this->assertTrue($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
  /**
   * @test
   */
  public function trueIfCouponOnCanceledEnrollment()
  {
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(array($this->oldCoupon)));
    $this->assertTrue($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
  /**
   * @test
   */
  public function trueIfCouponTwoCanceledEnrollmentsAgo()
  {
    $twoPrev = new A25_Record_Enroll();
    $twoPrev->Student = $student;
    $twoPrev->xref_id = '789';
    $twoPrev->status_id = A25_Record_Enroll::statusId_kickedOut;
    $this->prev->expects($this->any())->method('previousEnrollment')
        ->will($this->returnValue($twoPrev));
    
    $this->oldCoupon->xref_id = $twoPrev->xref_id;
    
    $this->plugin->expects($this->once())->method('existingCredits')
        ->will($this->returnValue(array($this->oldCoupon)));
    $this->assertTrue($this->plugin->alreadyHasCoupon($this->enroll,
        $this->coupon));
  }
}

class CouponWithMethodsExposed extends A25_Plugin_Coupons
{
  public function alreadyHasCoupon($enroll, $coupon) {
    return parent::alreadyHasCoupon($enroll, $coupon);
  }
}
