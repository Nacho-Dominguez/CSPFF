<?php

class test_unit_A25_Remind_Students_Payments_SecondReminder_MarkSentTest extends
		test_Framework_UnitTestCase
{ 
  /**
	 * @test
	 */
	public function marksEmailWasSent()
	{
    $enroll = $this->getMock('A25_Record_Enroll', array('save'));
    $enroll->expects($this->once())->method('save');
   
    $reminder = new SecondReminderWithMarkSentExposed();
    
    $reminder->markSent($enroll);
    
		$this->assertEquals(2, $enroll->sent_payment_reminder);
	}
}

class SecondReminderWithMarkSentExposed
    extends A25_Remind_Students_Payments_SecondReminder
{
  public function markSent($enroll) {
    return parent::markSent($enroll);
  }
}