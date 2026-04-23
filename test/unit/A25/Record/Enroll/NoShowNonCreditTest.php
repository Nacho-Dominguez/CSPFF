<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class NoShowNonCredit_A25_Record_Enroll extends A25_Record_Enroll
{
	public function NoShowNonCredit()
	{
		return parent::NoShowNonCredit();
	}
}

class test_unit_A25_Record_Enroll_NoShowNonCreditTest extends
		test_Framework_UnitTestCase
{
	private $nextXrefId = 1;
	private $student;
    private $config;
	public function setUp()
	{
		$this->student = new A25_Record_Student();
		parent::setUp();
        $this->config = A25_DI::PlatformConfig();
        $this->config->noShowsBeforeNoShowFee = 2;
	}
	/**
	 * @test
	 */
	public function firstNoShowDoesNothingToOrder()
	{
		$enroll = $this->addEnrollmentToStudentWithStatus('',false);
		$enroll->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$enroll->Order->expects($this->never())->method('A25_AddFeesWhenMarkingAsNoShow');

		$enroll->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function firstNoShowDoesNothingToOrder_ifStudentHasPaidCompleted()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_completed,true);

		$enroll = $this->addEnrollmentToStudentWithStatus('',false);
		$enroll->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$enroll->Order->expects($this->never())->method('A25_AddFeesWhenMarkingAsNoShow');

		$enroll->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function consecutiveNoShowsMakeOrderNonrefundable_ifStudentPaidForPrevious()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,true);

		$enroll = $this->addEnrollmentToStudentWithStatus('',true);
		$enroll->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$enroll->Order->expects($this->once())->method('A25_AddFeesWhenMarkingAsNoShow');

		$enroll->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function consecutiveNoShowDoesNothingToOrder_ifLastNoShowIsAlreadyNonrefundable()
	{
		$previousEnroll = $this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,true);
		$order = new A25_Record_Order();
		$order->Enrollment = $previousEnroll;
		$item = new A25_Record_OrderItem();
		$item->type_id =
					A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows;
		$item->Order = $order;

		$enroll = $this->addEnrollmentToStudentWithStatus('',true);
		$enroll->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$enroll->Order->expects($this->never())->method('A25_AddFeesWhenMarkingAsNoShow');

		$enroll->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function consecutiveNoShowsDoNothingToOrder_ifStudentHasNotPaid()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,false);

		$enroll = $this->addEnrollmentToStudentWithStatus('',false);
		$enroll->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$enroll->Order->expects($this->never())->method('A25_AddFeesWhenMarkingAsNoShow');

		$enroll->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function nonConsecutiveNoShowsMakeOrderNonrefundable_ifStudentHasPaidForPreviousNoShow()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,true);

		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_canceled,false);

		$three = $this->addEnrollmentToStudentWithStatus('',false);
		$three->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$three->Order->expects($this->once())->method('A25_AddFeesWhenMarkingAsNoShow');

		$three->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function noShowsSeparatedByKickedOutMakeOrderNonrefundable_ifStudentHasPaidForPreviousNoShow()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,true);

		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_kickedOut,false);

		$three = $this->addEnrollmentToStudentWithStatus('',false);
		$three->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$three->Order->expects($this->once())->method('A25_AddFeesWhenMarkingAsNoShow');

		$three->NoShowNonCredit();
	}
	/**
	 * @test
	 */
	public function doNotMakeOrderNonrefundable_ifStudentHasCompletedAfterPreviousNoShow()
	{
		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_noShow,true);

		$this->addEnrollmentToStudentWithStatus(
				A25_Record_Enroll::statusId_completed,false);

		$three = $this->addEnrollmentToStudentWithStatus('',false);
		$three->Order = $this->getMock('A25_Record_Order',
				array('A25_AddFeesWhenMarkingAsNoShow'));
		$three->Order->expects($this->never())->method('A25_AddFeesWhenMarkingAsNoShow');

		$three->NoShowNonCredit();
	}

	/**
	 * @param int $status_id
	 * @param boolean $paid
	 * @return A25_Record_Enroll (actually, a mock of it)
	 */
	private function addEnrollmentToStudentWithStatus($status_id,$paid)
	{
		$enroll = $this->getMock('NoShowNonCredit_A25_Record_Enroll',array('isPaid'));
		$enroll->expects($this->any())->method('isPaid')->will($this->returnValue($paid));
		$enroll->status_id = $status_id;
		$enroll->xref_id = $this->nextXrefId;
		$this->nextXrefId++;

		$this->student->Enrollments[] = $enroll;

		return $enroll;
	}
}
?>
