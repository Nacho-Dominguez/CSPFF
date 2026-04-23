<?php

class test_unit_A25_Record_Course_GetInstructorFeeTest
	extends test_Framework_UnitTestCase
{
	private $course;

	public function setUp()
	{
		$this->course = new A25_Record_Course();
	}

    /**
	 * @test
	 */
	public function returnZero_whenNoInstructors()
	{
		$this->expect(0);
	}

    /**
	 * @test
	 */
	public function returnInstructorFee_whenOneInstructors()
	{
		$fee = 10;
		$this->course->Instructor = $this->CreateInstructorWithFee($fee);

		$this->expect($fee);
	}

    /**
	 * @test
	 */
	public function returnCombinedInstructorFees_whenTwoInstructors()
	{
		$fee = 10;
		$fee2 = 20;
		$this->course->Instructor = $this->CreateInstructorWithFee($fee);
		$this->course->Instructor2 = $this->CreateInstructorWithFee($fee2);

		$this->expect($fee + $fee2);
	}

    /**
	 * @test
	 */
	public function returnZero_whenCourseIsCancelled()
	{
		$fee = 10;
		$fee2 = 20;
		$this->course->Instructor = $this->CreateInstructorWithFee($fee);
		$this->course->Instructor2 = $this->CreateInstructorWithFee($fee2);
		$this->course->status_id = A25_Record_Course::statusId_Cancelled;

		$this->expect(0);
	}

	private function CreateInstructorWithFee($fee)
	{
		$instructor = new A25_Record_User();
		$instructor->single_fee = $fee;
		$instructor->multiple_fee = $fee;

		return $instructor;
	}

	private function expect($expected)
	{
		$this->assertEquals($expected, $this->course->getInstructorFees());
	}
}
?>
