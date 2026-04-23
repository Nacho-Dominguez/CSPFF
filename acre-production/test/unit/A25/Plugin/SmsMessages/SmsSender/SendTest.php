<?php

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_SmsMessages_SmsSender_SendTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function sendsSms()
	{
    $message = 'This is the message';
    $to_number = '7203468133';
		$sms = $this->getMock('A25_SmsSender', array('create'));
    $sms->expects($this->once())
        ->method('create')
        ->with('7204662070', $to_number, $message);
    $this->assertTrue($sms->send($message, $to_number));
	}

	/**
	 * @test
	 */
	public function DoesNotSendSmsIfInvalidNumber()
	{
    $message = 'This is the message';
    $to_number = 'NOT A NUMBER';
		$sms = $this->getMock('A25_SmsSender', array('create'));
    $sms->expects($this->never())
        ->method('create');
    $this->assertFalse($sms->send($message, $to_number));
	}
}
