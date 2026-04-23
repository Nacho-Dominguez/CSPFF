<?php

require_once ServerConfig::webRoot . '/plugins/SmsMessages/A25/Remind/SmsToCourse.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */

class test_unit_A25_Remind_SmsToCourse_SendToIndividualTest extends
		test_Framework_UnitTestCase
{
  private $message;
  private $enroll;
  private $student;
  private $smssender;
  private $reminder;
  
	public function setUp()
	{
		parent::setUp();
    
		$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->evictTables();
    
    A25_ListenerManager::startUpWithListeners(array(new A25_Plugin_SmsMessages()));
    
    $this->message = 'This is a test';
    $this->enroll = new A25_Record_Enroll();
    $this->student = new A25_Record_Student();
    $this->enroll->Student = $this->student;
    
    $this->student->home_phone = '3035550100';
    $this->student->work_phone = '3035550111';
    
    $this->smssender = $this->getMock('A25_SmsSender');
    A25_SmsSender::setInstance($this->smssender);
    
    $this->reminder = new SmsToCourseWithSendToIndividualExposed($this->enroll->course_id,
        $this->message);
	}
	
	public function tearDown()
	{
    A25_ListenerManager::destroy();
    A25_ListenerManager::startUp();
		$conn = Doctrine_Manager::getInstance()->getCurrentConnection();
    $conn->evictTables();
	}
  
	/**
	 * @test
	 */
	public function sendsToNeitherPhoneIfNeitherChecked()
	{
    $this->student->home_sms = 0;
    $this->student->work_sms = 0;
    
    $this->smssender->expects($this->never())->method('send');
    
    $this->reminder->sendToIndividual($this->student);
	}
  
	/**
	 * @test
	 */
	public function sendsToHomePhoneIfHomeChecked()
	{
    $this->student->home_sms = 1;
    $this->student->work_sms = 0;
    
    $this->smssender->expects($this->once())->method('send')
        ->with($this->message, $this->student->home_phone);
    
    $this->reminder->sendToIndividual($this->student);
	}
  
	/**
	 * @test
	 */
	public function sendsToWorkPhoneIfWorkChecked()
	{
    $this->student->home_sms = 0;
    $this->student->work_sms = 1;
    
    $this->smssender->expects($this->once())->method('send')
        ->with($this->message, $this->student->work_phone);
    
    $this->reminder->sendToIndividual($this->student);
	}
  
	/**
	 * @test
	 */
	public function sendsToBothPhonesIfBothChecked()
	{
    $this->student->home_sms = 1;
    $this->student->work_sms = 1;
    
    $this->smssender->expects($this->at(0))->method('send')
        ->with($this->message, $this->student->home_phone);
    $this->smssender->expects($this->at(1))->method('send')
        ->with($this->message, $this->student->work_phone);
    
    $this->reminder->sendToIndividual($this->student);
	}
}

class SmsToCourseWithSendToIndividualExposed extends A25_Remind_SmsToCourse
{
  public function sendToIndividual($student) {
    return parent::sendToIndividual($student);
  }
}
