<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

class test_unit_A25_Envelope_SendTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function sends()
	{
    $content = new EmailContentForTest();
    $envelope = $this->getMock('A25_Envelope',
        array('subject', 'body', 'alt_body'), array($content));
    $mailer = $this->getMock('A25_Mailer');
    A25_DI::setMailer($mailer);
    
    $address = 'test@test.test';
    $mailer->expects($this->once())->method('mail')->with($address);
    
    $envelope->send($address, $content);
	}
}
