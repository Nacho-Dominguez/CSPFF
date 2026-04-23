<?php

class test_unit_A25_PaymentTimer_InsertTest extends
    test_Framework_UnitTestCase
{
  private $enroll;
  private $config;
  
  public function setUp()
  {
    parent::setUp();
    
    $this->config = A25_Di::PlatformConfig();
    $this->config->kickOutAfterDeadline = '30 minutes';
    
    $this->enroll = new A25_Record_Enroll();
    $this->enroll->status_id = A25_Record_Enroll::statusId_registered;
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime($this->config->kickOutAfterDeadline . '+ 29 minutes');
  }
	/**
	 * @test
	 */
	public function whenEnrollmentStatusIsRegisteredInsideOneHour()
	{
    $timer = $this->getMock('A25_PaymentTimer', array('timerHtml'),
        array($this->enroll));
    $timer->expects($this->once())->method('timerHtml');
    $timer->insert();
	}
  
	/**
	 * @test
	 */
	public function whenKickOutDateIsNull()
	{
    $this->enroll->kick_out_date = null;
    $timer = $this->getMock('A25_PaymentTimer', array('timerHtml'),
        array($this->enroll));
    $timer->expects($this->never())->method('timerHtml');
    $timer->insert();
	}
  
	/**
	 * @test
	 */
	public function whenKickOutDateIsOutsideOneHour()
	{
    $this->enroll->kick_out_date = A25_Functions::formattedDateTime($this->config->kickOutAfterDeadline . '+ 31 minutes');
    $timer = $this->getMock('A25_PaymentTimer', array('timerHtml'),
        array($this->enroll));
    $timer->expects($this->never())->method('timerHtml');
    $timer->insert();
	}
  
	/**
	 * @test
	 */
	public function whenEnrollmentStatusNotActive()
	{
    $this->enroll->status_id = A25_Record_Enroll::statusId_canceled;
    $timer = $this->getMock('A25_PaymentTimer', array('timerHtml'),
        array($this->enroll));
    $timer->expects($this->never())->method('timerHtml');
    $timer->insert();
  }
}
