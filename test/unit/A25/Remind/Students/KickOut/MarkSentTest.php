<?php

class test_unit_A25_Remind_Students_KickOut_MarkSentTest extends
test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function cancelsEnrollment()
	{
    $enroll = $this->getMock('A25_Record_Enroll', array('save'));
    // Expected twice since we call save() twice in saveAfterApplyingBusinessRules()
    $enroll->expects($this->exactly(2))->method('save');
   
    $reminder = new KickOutWithMarkSentExposed();
    
    $reminder->markSent($enroll);
    
		$this->assertEquals(A25_Record_Enroll::statusId_kickedOut, $enroll->status_id);
	}
}

class KickOutWithMarkSentExposed extends A25_Remind_Students_KickOut
{
  public function markSent($enroll) {
    return parent::markSent($enroll);
  }
}
