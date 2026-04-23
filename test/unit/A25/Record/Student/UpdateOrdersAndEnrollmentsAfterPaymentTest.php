<?php

class test_unit_A25_Record_Student_UpdateOrdersAndEnrollmentsAfterPaymentTest extends
		test_Framework_UnitTestCase
{
	private $_student;
	private $_lineitem;
	private $_order;

	public function setUp()
	{
		parent::setUp();
		$this->_student = $this->getMock('A25_Record_Student', array('save'));
		$enroll = new A25_Record_Enroll();
		$this->_student->Enrollments[] = $enroll;
		$this->_order = new A25_Record_Order();
		$this->_order->Enrollment = $enroll;
		$this->_lineitem = $this->makeLineItem();


		$mailer = $this->mock('A25_Mailer');
		A25_DI::setMailer($mailer);

		$mailer->expects($this->never())->method('mail');
	}

	/**
	 * @test
	 */
	public function OrderItemMarkedPaid_WhenZeroAcountBalance()
	{
		$this->makePayment();

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertTrue($this->_lineitem->isPaid());
		$this->assertEquals(date('Y-m-d'), $this->_lineitem->date_paid);
	}
	/**
	 * @test
	 */
	public function OrderItemNotMarkedPaid_WhenStudentOwes()
	{
		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertFalse($this->_lineitem->isPaid());
	}

	/**
	 * @test
	 */
	public function NewestOrderItemNotMarkedPaid_WhenStudentPaysPartial()
	{
		$newestItem = $this->makeLineItem();

		$this->makePayment();

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();

		$this->assertTrue($this->_lineitem->isPaid());
		$this->assertFalse($newestItem->isPaid());
	}

	/**
	 * @test
	 */
	public function OldDatePaidIsNotOverwritten()
	{
		$this->makePayment();

		$datePaid = date('Y-m-d', strtotime('-1 day'));
		$this->_lineitem->date_paid = $datePaid;

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertEquals($datePaid, $this->_lineitem->date_paid);
	}
	/**
	 * @test
	 */
	public function OrderMarkedPaid_WhenZeroAcountBalance()
	{
		$this->makePayment();

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertTrue($this->_order->isPaid());
	}
	/**
	 * @test
	 */
	public function OrderMarkedNotPaided_WhenNotAllLineItemsArePaid()
	{
		$this->makePayment();

		$newLineItem = $this->makeLineItem();

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertFalse($this->_order->isPaid());
	}
	/**
	 * @test
	 */
	public function callsSave()
	{
		$this->_student->expects($this->once())->method('save');
		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
	}
	/**
	 * @test
	 */
	public function updatesEnrollmentStatus_WhenOrderIsPaid()
	{
		$this->makePayment();

		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertEquals(A25_Record_Enroll::statusId_student,
				$this->_order->getStatusId());
	}
	/**
	 * @test
	 */
	public function doesNotUpdateEnrollmentStatus_WhenOrderIsNotPaid()
	{
		$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		$this->assertEquals(null, $this->_order->getStatusId());
	}
	private function makePayment()
	{
		$payment = new A25_Record_Pay();
		$payment->amount = $this->_lineitem->chargeAmount();
		$payment->Order = $this->_order;
    $payment->Student = $this->_student;
	}
	private function makeLineItem()
	{
		$lineitem = new A25_Record_OrderItem();
		$lineitem->Order = $this->_order;
		$lineitem->quantity = 1;
		$lineitem->unit_price = 15;

		return $lineitem;
	}
}