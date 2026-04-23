<?php
class A25_DrivingPermitDiscount
{
	protected static function eligibleForPermit(A25_Record_Student $student,
			A25_Record_Course $course)
	{
		$age = $student->age(
				strtotime($course->course_start_date));

		return ($age < 17 &&
				! $student->alreadyBeenLicensedOrHasDrivingPermit() &&
				! self::hasStudentAttendedACourse($student));
	}

	public static function tuitionFee(A25_Record_Enroll $enroll,
			A25_Record_Order $order, A25_Record_Course $course, $extraFee)
	{
		if (self::studentGetsDiscount($enroll))
		{
			$fee = PlatformConfig::discountedDrivingPermitTuition;
			
			// If the tuition for the particular course is lower than the
			// discount for Driving Permit, use the default tuition.
			$course_fee = $course->getSetting('fee');
			if ($fee > $course_fee)
				$fee = $course_fee;
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
				&& $enroll->hear_about_id != 1 // Court Referral
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

	private static function hasStudentAttendedACourse(A25_Record_Student $student)
	{
		foreach ($student->Enrollments as $enroll)
		{
			if ($enroll->hasBeenAttended())
			{
				return true;
			}
		}

		return false;
	}
}
