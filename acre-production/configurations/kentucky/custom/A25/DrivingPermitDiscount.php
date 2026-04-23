<?php
class A25_DrivingPermitDiscount
{
    // For Kentucky, the student needs to have a permit or license to be eligible for free tuition
    public static function eligibleForPermit(A25_Record_Student $student,
			A25_Record_Course $course)
	{
		$age = $student->age(
				strtotime($course->course_start_date));

        if ($student->license_status == A25_Record_Student::licenseStatus_unlicensed) {
            return false;
        }
        if ($age < 22) {
            return true;
        }
        return false;
	}

	public static function tuitionFee(A25_Record_Enroll $enroll,
			A25_Record_Order $order, A25_Record_Course $course, $extraFee)
	{
		if (self::studentGetsDiscount($enroll))
		{
			$fee = PlatformConfig::discountedDrivingPermitTuition;
		} else {
			$fee = $course->getSetting('fee');
		}

		$order->createLineItem(
				A25_Record_OrderItemType::typeId_CourseFee,
				$fee + $extraFee);
	}
  
  public static function studentGetsDiscount(A25_Record_Enroll $enroll)
  {
		if ($enroll->reason_id ==
				A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit
				&& self::eligibleForPermit($enroll->Student, $enroll->Course))
    {
      return true;
    }
    return false;
  }

	public static function appendReasonListQuery(A25_Query $q,
			A25_Record_Student $student, A25_Record_Course $course = null)
	{
		if (!$course)
			return $q;

		if (!self::eligibleForPermit($student, $course)) {
			$q->andWhere('r.reason_id <> ?',
					A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit);
		}

		return $q;
	}
}
