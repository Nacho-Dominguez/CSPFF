<?php

class test_unit_A25_Record_Student_AlreadyBeenLicensedTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function validLicense_returnsTrue()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_valid;
		$this->assertTrue($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * @test
	 */
	public function canceledLicense_returnsTrue()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_canceled;
		$this->assertTrue($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * @test
	 */
	public function probationLicense_returnsTrue()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_probation;
		$this->assertTrue($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * @test
	 */
	public function suspendedLicense_returnsTrue()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_suspended;
		$this->assertTrue($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * @test
	 */
	public function unlicensed_returnsFalse()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_unlicensed;
		$this->assertFalse($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * @test
	 */
	public function drivingPermit_returnsTrue()
	{
		$student = new A25_Record_Student();
		$student->license_status = A25_Record_Student::licenseStatus_drivingPermit;
		$this->assertTrue($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * A license_status of '0' is not a valid choice, but there are many
	 * students in the DB with a license_status of 0, so we will treat them as
	 * not yet licensed.
	 *
	 * @test
	 */
	public function zero_returnsFalse()
	{
		$student = new A25_Record_Student();
		$student->license_status = 0;
		$this->assertFalse($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
	/**
	 * A license_status of NULL is not a valid choice, but there are many
	 * students in the DB with a NULL license_status, so we will treat them as
	 * not yet licensed.
	 *
	 * @test
	 */
	public function null_returnsFalse()
	{
		$student = new A25_Record_Student();
		$student->license_status = null;
		$this->assertFalse($student->alreadyBeenLicensedOrHasDrivingPermit());
	}
}
?>
