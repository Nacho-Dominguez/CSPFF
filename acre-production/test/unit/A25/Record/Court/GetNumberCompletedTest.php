<?php

class test_unit_A25_Record_Court_GetNumberCompletedTest extends
		test_Framework_UnitTestCase
{
	private $court;

	public function setUp()
	{
		parent::setUp();
		$this->court = new A25_Record_Court();
	}

	/**
	 * @test
	 */
	public function returnsZero_whenNoEnrollments()
	{
		$this->expect(0);
	}
	/**
	 * @test
	 */
	public function returnsZero_whenNotCompleted()
	{
		$this->createEnrollmentWithStatus(A25_Record_Enroll::statusId_registered);
		$this->expect(0);
	}
	/**
	 * @test
	 */
	public function returnsOne_whenCompleted()
	{
		$this->createEnrollmentWithStatus(A25_Record_Enroll::statusId_completed);
		$this->expect(1);
	}

	private function createEnrollmentWithStatus($status)
	{
		$enroll = new A25_Record_Enroll();
		$enroll->status_id = $status;
		$this->court->Enrollments[] = $enroll;
	}

	private function expect($expected)
	{
		$this->assertEquals($expected,$this->court->getNumberCompleted());
	}
}