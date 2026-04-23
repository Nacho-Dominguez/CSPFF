<?php

class test_unit_A25_DoctrineRecord_SyncInverseRelationTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsBothSidesOfAOneToManyWhenUsingAlias()
	{
		$student = new A25_Record_Student();

		$enroll = new A25_Record_Enroll();
		$enroll->Student = $student;
		$this->assertEquals(1, count($student->Enrollments));
	}
	/**
	 * @test
	 */
	public function setsBothSidesOfAOneToManyWhenUsingAlias2()
	{
		$order = new A25_Record_Order();
		$order->order_id = 123;

		$item = new A25_Record_OrderItem();
		$item->Order = $order;
		$this->assertEquals(1, count($order->OrderItems));
	}
	/**
	 * @test
	 */
	public function setsBothSidesOfAOneToOneWhenUsingAlias()
	{
		$enroll = new A25_Record_Enroll();

		$order = new A25_Record_Order();
		$order->order_id = 123;
		$order->Enrollment = $enroll;
		$this->assertEquals(123, $enroll->Order->order_id);
	}
	/**
	 * @test
	 */
	public function IfStudentAlreadyHasEnroll_NotAdded()
	{
		$student = new A25_Record_Student();

		$enroll = new A25_Record_Enroll();

		$student->Enrollments[] = $enroll;
		$enroll->Student = $student;
		
		$this->assertEquals(1, count($student->Enrollments));
	}
	/**
	 * @test
	 */
	public function setsBothSidesOfAManyToOne()
	{
		$student = new A25_Record_Student();
		$student->student_id = 789;

		$enroll = new A25_Record_Enroll();
		$student->Enrollments[] = $enroll;
		$this->assertEquals($student->student_id, $enroll->Student->student_id);
	}
}