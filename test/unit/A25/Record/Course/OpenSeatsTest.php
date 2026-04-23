<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class unit_Course_OpenSeats_ActiveEnroll extends A25_Record_Enroll
{
	public function occupiesSeat()
	{
		return true;
	}
}

class unit_Course_OpenSeats_InactiveEnroll extends A25_Record_Enroll
{
	public function occupiesSeat()
	{
		return false;
	}
}

class test_unit_A25_Record_Course_OpenSeatsTest extends test_Framework_UnitTestCase {
    /**
	 * @test
	 */
	public function returnsTotalSeatsIfNoEnrollments()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_capacity = 7;
		$this->assertEquals(7, $courseRecord->openSeats());
    }
    /**
	 * @test
	 */
	public function subtractsActiveEnrollment()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_capacity = 7;
		$courseRecord->Enrollments[] = new unit_Course_OpenSeats_ActiveEnroll();
		$this->assertEquals(6, $courseRecord->openSeats());
    }
    /**
	 * @test
	 */
	public function ignoresInactiveEnrollment()
	{
		$courseRecord = new A25_Record_Course();
		$courseRecord->course_capacity = 7;
		$courseRecord->Enrollments[] = new unit_Course_OpenSeats_InactiveEnroll();
		$this->assertEquals(7, $courseRecord->openSeats());
    }
}
