<?php

class test_unit_A25_Remind_Students_Payments_FirstReminder_MarkSentTest extends
		test_Framework_UnitTestCase
{ 
  /**
	 * @test
	 */
	public function marksEmailWasSent()
	{
    $enroll = $this->getMock('A25_Record_Enroll', array('save'));
    $enroll->expects($this->once())->method('save');
   
    $reminder = new FirstReminderWithMarkSentExposed();
    
    $reminder->markSent($enroll);
    
		$this->assertEquals(1, $enroll->sent_payment_reminder);
	}
}

class FirstReminderWithMarkSentExposed
    extends A25_Remind_Students_Payments_FirstReminder
{
  public function markSent($enroll) {
    return parent::markSent($enroll);
  }
}