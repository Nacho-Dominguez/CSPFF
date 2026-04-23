<?php

/**
 * This class is for writing tests that uncover Doctrine's default behaviors.
 * Whenever I am not sure how Doctrine will behave, write a test for it here.
 *
 * These tests also provide important regression tests for my assumptions about
 * how Doctrine behaves.  If they change a behavior, I may need to adjust my
 * code.
 */
class test_unit_A25_DoctrineRecord_StrangeDefaultBehaviorsTest extends
		test_Framework_UnitTestCase
{
	/**
	 * Doctrine sorts by the 'orderBy' if it's defined for an alias, or else it
	 * just takes the default sort of the database engine.  However, if
	 * the relationship is dynamically modified, the records are no longer
	 * guaranteed to be in order.
	 * 
	 * @test
	 */
	public function OneToManyAliasIsNotNecessarilySortedByPrimaryKey()
	{
		$student = new A25_Record_Student();

		$enroll1 = new A25_Record_Enroll();
		$enroll1->xref_id = 1;

		$enroll2 = new A25_Record_Enroll();
		$enroll2->xref_id = 2;

		$student->Enrollments[] = $enroll2;
		$student->Enrollments[] = $enroll1;
		$this->assertEquals($enroll2->xref_id, $student->Enrollments[0]->xref_id);
	}
	/**
	 * @test
	 */
	public function ManyToOneAliasesSynchTheOtherSide()
	{
		$student = new JosStudent();
		$student->student_id = 567;
		$enroll = new JosStudentCourseXref();
		$student->Enrollments[] = $enroll;
		$this->assertEquals(567, $enroll->Student->student_id);
	}
	/**
	 * When this tests starts failing, it will be a happy day, because I will
	 * be able to clean up the __set() function in DoctrineRecord, since Doctrine
	 * will be handling synching for me.
	 *
	 * @test
	 */
	public function OneToManyAliasesDoNotSynchTheOtherSide()
	{
		$student = new JosStudent();
		$enroll = new JosStudentCourseXref();
		$enroll->Student = $student;
		$this->assertEquals(0, count($student->Enrollments));
	}
	/**
	 * When this tests starts failing, it will be a happy day, because I will
	 * be able to clean up the __set() function in DoctrineRecord, since Doctrine
	 * will be handling synching for me.
	 *
	 * @test
	 */
	public function OneToOneAliasesDoNotSynchTheOtherSide()
	{
		$order = new JosOrder();
		$enroll = new JosStudentCourseXref();
		$enroll->xref_id = 123;
		$enroll->Order = $Order;
		$this->assertFalse(isset($order->Enrollment));
	}

	/**
	 * We wish this test would fail.
	 * @test
	 */
	public function mockingInterferesWithOneToManyRelationshipSyncing()
	{
		// One-to-many does not work:
		$student = new A25_Record_Student();
		$enroll = $this->getMock('A25_Record_Enroll',array('mockNothing'));
		$enroll->Student = $student;
		$this->assertEquals(0, count($student->Enrollments));

		// Many-to-one works:
		$student2 = new A25_Record_Student();
		$student2->student_id = 56;
		$enroll2 = $this->getMock('A25_Record_Enroll',array('mockNothing'));
		$student2->Enrollments[] = $enroll2;
		$this->assertEquals($student2->student_id, $enroll2->Student->student_id);

		// One-to-one works:
		$order3 = new A25_Record_Order();
		$order3->order_id = 123;
		$enroll3 = $this->getMock('A25_Record_Enroll',array('mockNothing'));
		$order3->Enrollment = $enroll3;
		$this->assertEquals($order3->order_id,$enroll3->Order->order_id);
	}
}