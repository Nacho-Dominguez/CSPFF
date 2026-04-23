<?php

class test_unit_A25_Listing_BrowseCourses_EnrollLinkTest extends
		test_Framework_UnitTestCase
{
    /**
	 * @test
	 */
	public function linksToOpenClass()
	{
		$course = new unit_EnrollLink_Course_NotPastPaymentOptionDeadline();
		$course->course_id = 123;

		$report = new unit_EnrollLink_BrowseCourses();
		$this->assertEquals('<a class="enroll_link" rel="nofollow" href="'
				. A25_Link::withoutSef("course-info?course_id=$course->course_id")
				. '">Enroll</a>', $report->enrollLink($course));
    }
    /**
	 * @test
	 */
	public function asteriskIfCreditCardPaymentRequired()
	{
		$course = new unit_EnrollLink_Course_PastPaymentOptionDeadline();
		$course->course_capacity = 1;
		$course->course_id = 123;

		$report = new unit_EnrollLink_BrowseCourses();
		$this->assertEquals('<a class="enroll_link" rel="nofollow" href="'
				. A25_Link::withoutSef("course-info?course_id=$course->course_id")
				. '">Enroll</a> <span class="asterisk">*</span>',
			$report->enrollLink($course));
    }
}

class unit_EnrollLink_BrowseCourses extends A25_Listing_BrowseCourses
{
	public function enrollLink(A25_Record_Course $course)
	{
		return parent::enrollLink($course);
	}
}

class unit_EnrollLink_Course_NotPastPaymentOptionDeadline extends A25_Record_Course
{
	public function isPastPaymentOptionDeadline()
	{
		return false;
	}
}
class unit_EnrollLink_Course_PastPaymentOptionDeadline extends A25_Record_Course
{
	public function isPastPaymentOptionDeadline()
	{
		return true;
	}
}