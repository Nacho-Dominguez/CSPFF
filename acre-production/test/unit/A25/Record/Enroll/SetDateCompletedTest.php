<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Enroll_SetDateCompletedTest_Enroll extends
		A25_Record_Enroll
{
	public function setDateCompleted()
	{
		return parent::setDateCompleted();
	}
}

class test_unit_A25_Record_Enroll_SetDateCompletedTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function SetsDateIfCompletedAndDateIsNull()
	{
		$enroll = new test_unit_A25_Record_Enroll_SetDateCompletedTest_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_completed;
		$enroll->date_completed == null;
		$enroll->setDateCompleted();
		$this->assertEquals(date('Y-m-d',time()),$enroll->date_completed);
	}

	/**
	 * @test
	 */
	public function SetsDateIfCompletedAndDateIsZeroes()
	{
		$enroll = new test_unit_A25_Record_Enroll_SetDateCompletedTest_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_completed;
		$enroll->date_completed == '0000-00-00';
		$enroll->setDateCompleted();
		$this->assertEquals(date('Y-m-d',time()),$enroll->date_completed);
	}
	/**
	 * @test
	 */
	public function LeavesDateAloneIfNotCompleted()
	{
		$enroll = new test_unit_A25_Record_Enroll_SetDateCompletedTest_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_canceled;
		$enroll->date_completed = '0000-00-00';
		$enroll->setDateCompleted();
		$this->assertEquals('0000-00-00',$enroll->date_completed);
	}
	/**
	 * @test
	 */
	public function LeavesDateAloneIfAlreadySet()
	{
		$enroll = new test_unit_A25_Record_Enroll_SetDateCompletedTest_Enroll();
		$enroll->status_id = A25_Record_Enroll::statusId_completed;
		$enroll->date_completed = '2010-01-01';
		$enroll->setDateCompleted();
		$this->assertEquals('2010-01-01',$enroll->date_completed);
	}
}
?>
