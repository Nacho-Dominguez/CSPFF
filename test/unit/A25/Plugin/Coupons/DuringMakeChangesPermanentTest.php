<?php

require_once ServerConfig::webRoot . '/plugins/Coupons.php';

class test_unit_A25_Plugin_Coupons_DuringMakeChangesPermanentTest extends
    test_Framework_UnitTestCase
{
  private $enroll;
  private $plugin;
  private $coupon;
  
  public function setUp()
  {
    parent::setUp();
    
    $_REQUEST = null;
    
    $this->enroll = new A25_Record_Enroll;
    $this->plugin = $this->getMock('A25_Plugin_Coupons',
        array('getCoupon', 'createCredit', 'alreadyHasCoupon'));
    $this->coupon = new A25_Record_CreditType();
    $this->coupon->credit_type_id = '234';
  }
  
  public function tearDown()
  {
    parent::tearDown();
    $_REQUEST = null;
  }
  
  /**
   * @test
   */
  public function noCouponIfNoCode()
  {
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_Insurance;
    $_REQUEST['coupon'] = '';
    $this->plugin->expects($this->never())->method('getCoupon');
    $this->plugin->expects($this->never())->method('createCredit');
    $this->plugin->duringMakeChangesPermanent($this->enroll);
  }
  
  /**
   * @test
   */
  public function noCouponIfCourtOrdered()
  {
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_CourtOrdered;
    $_REQUEST['coupon'] = 'code123';
    $this->plugin->expects($this->never())->method('getCoupon');
    $this->plugin->expects($this->never())->method('createCredit');
    $this->plugin->duringMakeChangesPermanent($this->enroll);
  }
  
  /**
   * @test
   */
  public function noCouponIfWrongCode()
  {
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_Insurance;
    $_REQUEST['coupon'] = 'code123';
    $this->plugin->expects($this->any())->method('getCoupon')->will($this->returnValue(null));
    $this->plugin->expects($this->never())->method('createCredit');
    $this->plugin->duringMakeChangesPermanent($this->enroll);
  }
  
  /**
   * @test
   */
  public function noCouponIfAlreadyHasCoupon()
  {
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_Insurance;
    $_REQUEST['coupon'] = 'code123';
    $this->plugin->expects($this->any())->method('getCoupon')
        ->will($this->returnValue($this->coupon));
    $this->plugin->expects($this->once())->method('alreadyHasCoupon')
        ->will($this->returnValue(true));
    $this->plugin->expects($this->never())->method('createCredit');
    $this->plugin->duringMakeChangesPermanent($this->enroll);
  }
  
  /**
   * @test
   */
  public function couponIfRequirementsMet()
  {
    $this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_Insurance;
    $_REQUEST['coupon'] = 'code123';
    $this->plugin->expects($this->any())->method('getCoupon')
        ->will($this->returnValue($this->coupon));
    $this->plugin->expects($this->once())->method('alreadyHasCoupon')
        ->will($this->returnValue(false));
    $this->plugin->expects($this->once())->method('createCredit');
    $this->plugin->duringMakeChangesPermanent($this->enroll);
  }
}
