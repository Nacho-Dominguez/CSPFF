<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');
class test_unit_A25_Record_Enroll_MarkAsNoShowTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function callsNoShowNonCredit_whenStatusIsNotNoShow()
	{
		$enroll = $this->enrollmentWithStatusOf(A25_Record_Enroll::statusId_completed);

		$enroll->expects($this->once())->method('NoShowNonCredit');

		$enroll->markAsNoShow();
	}
	/**
	 * @test
	 */
	public function ChangesStatusOfEnrollment()
	{
		$enroll = $this->enrollmentWithStatusOf(A25_Record_Enroll::statusId_completed);

		$enroll->markAsNoShow();

		$this->assertEquals(A25_Record_Enroll::statusId_noShow,
				$enroll->status_id);
	}
	/**
	 *
	 * @param int $status_id
	 * @return A25_Record_Enroll (actually, a mock of it)
	 */
	private function enrollmentWithStatusOf($status_id)
	{
		$enroll = $this->getMock('A25_Record_Enroll', array('checkAndStore',
				'NoShowNonCredit'));
		$enroll->status_id = $status_id;
    $student = $this->getMock('A25_Record_Student', array('updateOrdersAndEnrollmentsAfterPayment'));
    $enroll->Student = $student;
		
		return $enroll;
	}
}
?>
