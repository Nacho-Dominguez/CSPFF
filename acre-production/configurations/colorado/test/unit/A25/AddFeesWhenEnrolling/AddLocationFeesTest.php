<?php
class unit_AddLocationFeesTest_A25_AddFeesWhenEnrolling
		extends A25_AddFeesWhenEnrolling
{
	public $_orderRecord;
	public $_enroll;
	public function addLocationFees(A25_Record_Course $course,
			$extraFee)
	{
		return parent::_addLocationFees($course, $extraFee);
	}
}

class test_unit_A25_AddFeesWhenEnrolling_AddLocationFeesTest
		extends test_Framework_UnitTestCase
{
	const AGE_16 = '1993-07-28';
	const AGE_17 = '1993-07-27';
	private $course;
	private $enroll;
	private $fee;
	private $order;

	public function setUp()
	{
		$this->enroll = new A25_Record_Enroll();
		$this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;
		$this->enroll->Student = new A25_Record_Student();
		$this->enroll->Student->date_of_birth = self::AGE_16;
		$this->enroll->Student->license_status =
				A25_Record_Student::licenseStatus_unlicensed;

		$this->course = new A25_Record_Course();
		$this->course->course_start_date = '2010-07-27 08:00:00';
		$this->course->Location = new A25_Record_Location();
		$this->course->Location->fee = 79;
		$this->enroll->Course = $this->course;

		$this->order = $this->getMock('A25_Record_Order');

		$this->fee = new unit_AddLocationFeesTest_A25_AddFeesWhenEnrolling(
				$this->enroll, $this->order);
	}
	/**
	 * @test
	 */
	public function createsLineItemWithExtraFeeAdded()
	{
		$this->enroll->Student->date_of_birth = self::AGE_17;
		$extraFee = 10;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee') + $extraFee);
		$this->callAddLocationFees($extraFee);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithDiscountedFee_whenObtainEarlyPermitStudentIsUnder17()
	{
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						PlatformConfig::discountedDrivingPermitTuition);
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithLocationFee_whenObtainEarlyPermitStudentIsOver17()
	{
		$this->enroll->Student->date_of_birth = self::AGE_17;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee'));
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithLocationFee_whenStudentIsUnder17_ButNotDrivingPermit()
	{
		$this->enroll->reason_id = A25_Record_ReasonType::reasonTypeId_ParentsRequired;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee'));
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithLocationFee_whenCourtReferral()
	{
		$this->enroll->hear_about_id = 1; // Court Referral
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee'));
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithLocationFee_whenStudentIsLicensed()
	{
		$this->enroll->Student->license_status =
				A25_Record_Student::licenseStatus_valid;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee'));
		$this->callAddLocationFees(0);
	}
	/**
	 * @test
	 */
	public function createsLineitemWithLocationFee_whenStudentHasDrivingPermit()
	{
		$this->enroll->Student->license_status =
				A25_Record_Student::licenseStatus_drivingPermit;
		$this->order->expects($this->once())->method('createLineItem')
				->with(A25_Record_OrderItemType::typeId_CourseFee,
						$this->course->getSetting('fee'));
		$this->callAddLocationFees(0);
	}

	private function callAddLocationFees($extraFees)
	{
		$this->fee->addLocationFees($this->course, $extraFees);
	}
}
