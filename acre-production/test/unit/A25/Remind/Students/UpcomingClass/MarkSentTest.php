<?php

class test_unit_A25_Remind_Students_UpcomingClass_MarkSentTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function marksEmailWasSent()
	{
    $enroll = $this->getMock('A25_Record_Enroll', array('save'));
    $enroll->expects($this->once())->method('save');

    $reminder = new MarkUpcomingClassSentWithMethodsExposed();

    $reminder->markSent($enroll);

		$this->assertEquals(1, $enroll->sent_class_reminder);
	}
}

class MarkUpcomingClassSentWithMethodsExposed extends A25_Remind_Students_UpcomingClass_FirstReminder
{
  public function markSent($enroll) {
    return parent::markSent($enroll);
  }
}
