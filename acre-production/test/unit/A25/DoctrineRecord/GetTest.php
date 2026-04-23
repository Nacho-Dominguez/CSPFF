<?php

class test_unit_A25_DoctrineRecord_GetTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function alwaysGetsProperty()
	{
		$student = new A25_Record_Student;
		$student->student_id = 3;
		$this->assertEquals(3,$student->student_id);
	}

	/**
	 * @test
	 */
	public function getsDefinedRelative()
	{
		$enroll = new A25_Record_Enroll();
		$court = new A25_Record_Court();
		$court->court_id = 7;
		$enroll->Court = $court;
		$this->assertEquals(7,$enroll->Court->court_id);
	}

	/**
	 * @test
	 */
	public function getsPropertyThatIsAlsoARelationship()
	{
		$location = new A25_Record_Location();
		
		// For some reason, using empty() throws exceptions when other uses
		// don't, so it is important to test with it here.
		$settingThisShouldNotThrowAnExcetpion = empty($location->zip);
	}

	/**
	 * @test
	 */
	public function getsUndefinedArrayRelatives()
	{
		$student = new A25_Record_Student();
		$settingThisShouldNotThrowAnExcetpion = $student->Enrollments;
	}

	/**
	 * @test
	 *
	 * @expectedException Exception
	 */
	public function throwsExceptionOnSingleNullRelative()
	{

		$enroll = new A25_Record_Enroll();
		$settingThisShouldThrowAnExcetpion = $enroll->Student;
	}
}