<?php

class test_unit_A25_Record_Court_GetNumberRegisteredTest extends
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
	public function returnsZero_whenNoneAreRegistered()
	{
		$this->expect(0);
	}
	/**
	 * @test
	 */
	public function returnsOne_whenOneRegistered()
	{
		$this->createEnrollmentWithStatus(A25_Record_Enroll::statusId_registered);
		$this->expect(1);
	}
	/**
	 * @test
	 */
	public function returnsZero_whenOneCanceled()
	{
		$this->createEnrollmentWithStatus(A25_Record_Enroll::statusId_canceled);
		$this->expect(0);
	}
	/**
	 * @test
	 */
	public function returnsZero_whenOneKickedOut()
	{
		$this->createEnrollmentWithStatus(A25_Record_Enroll::statusId_kickedOut);
		$this->expect(0);
	}

	private function createEnrollmentWithStatus($status)
	{
		$enroll = new A25_Record_Enroll();
		$enroll->status_id = $status;
		$this->court->Enrollments[] = $enroll;
	}

	private function expect($expected)
	{
		$this->assertEquals($expected,$this->court->getNumberRegistered());
	}
}