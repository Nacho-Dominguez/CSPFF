<?php

class test_unit_A25_Listing_BrowseCourses_ActionColumnTest extends
		test_Framework_UnitTestCase
{
    /**
	 * @test
	 */
	public function openClass()
	{
		$course = new unit_ActionColumn_Course_NotPastPaymentOptionDeadline();
		$course->course_capacity = 1;
		$course->course_id = 123;

		$report = new unit_ActionColumn_BrowseCourses();
		$this->assertEquals($report->seatsAndEnroll($course),
				$report->actionColumn($course));
  }
    /**
	 * @test
	 */
	public function saysCourseIsFull()
	{
		$course = new unit_ActionColumn_Course_NotPastPaymentOptionDeadline();
		$course->course_capacity = 0;
		$course->course_id = 123;

		$report = new unit_ActionColumn_BrowseCourses();
		$this->assertEquals('<div class="no_enroll">Class is Full</div>',
				$report->actionColumn($course));
  }
    /**
	 * Although this test may seem redundant, it covers a bug which originally
	 * made it to production.
	 *
	 * @test
	 */
	public function whenClassIsOverfilled_saysCourseIsFull()
	{
		$course = new unit_ActionColumn_Course_NotPastPaymentOptionDeadline();
		$course->course_capacity = -1;
		$course->course_id = 123;

		$report = new unit_ActionColumn_BrowseCourses();
		$this->assertEquals('<div class="no_enroll">Class is Full</div>',
				$report->actionColumn($course));
  }
    /**
	 * @test
	 */
	public function pastEnrollmentDeadline()
	{
		$course = new unit_ActionColumn_Course_PastEnrollmentDeadline();

		$report = new unit_ActionColumn_BrowseCourses();
		$this->assertEquals('<div class="no_enroll">Registration Closed</div>',
				$report->actionColumn($course));
  }
    /**
	 * @test
	 */
	public function addCourseTypeMessage()
	{
		$course = new unit_ActionColumn_Course_NotPastPaymentOptionDeadline();
		$course->course_capacity = 1;
		$course->course_id = 123;
		$course->course_type_id = Config_CourseTypes::HIGH_SCHOOL;

		$report = new unit_ActionColumn_BrowseCourses();
		$types = new Config_CourseTypes();
		$this->assertEquals(
			$report->seatsAndEnroll($course) . $types->actionColumn($course),
			$report->actionColumn($course));
  }
    /**
	 * @test
	 */
	public function addsLateFeeMessage()
	{
		$course = new unit_ActionColumn_Course_PastLateFeeDeadline();
		$course->course_capacity = 1;
		$course->course_id = 123;
    $course->late_fee = 10;
    $course->late_fee_deadline = 24;

		$report = new unit_ActionColumn_BrowseCourses();
		$this->assertEquals('<div class="seats_left">1 seats left</div><div class="enroll"><a class="enroll_link" rel="nofollow" href="' . ServerConfig::staticHttpUrl() . 'course-info?course_id=123">Enroll</a> <span class="asterisk">*</span></div><div class="late_fee">(Since class is within 24 hours, there is a late registration fee of $10)</div>',
				$report->actionColumn($course));
  }
}

class unit_ActionColumn_BrowseCourses extends A25_Listing_BrowseCourses
{
	public function actionColumn(A25_Record_Course $course)
	{
		return parent::actionColumn($course);
	}
	public function seatsAndEnroll(A25_Record_Course $course)
	{
		return parent::seatsAndEnroll($course);
	}
}

class unit_ActionColumn_Course_NotPastPaymentOptionDeadline extends A25_Record_Course
{
	public function isPastPaymentOptionDeadline()
	{
		return false;
	}
  public function isPastLateFeeDeadline()
  {
    return false;
  }
	public function isPastEnrollmentDeadline()
	{
		return false;
	}
}
class unit_ActionColumn_Course_PastLateFeeDeadline extends A25_Record_Course
{
	public function isPastPaymentOptionDeadline()
	{
		return true;
	}
  public function isPastLateFeeDeadline()
  {
    return true;
  }
	public function isPastEnrollmentDeadline()
	{
		return false;
	}
}
class unit_ActionColumn_Course_PastEnrollmentDeadline extends A25_Record_Course
{
	public function isPastPaymentOptionDeadline()
	{
		return true;
	}
  public function isPastLateFeeDeadline()
  {
    return true;
  }
	public function isPastEnrollmentDeadline()
	{
		return true;
	}
}
