<?php
class unit_GetNumberOfStudentsWithGender_A25_Record_Court
		extends A25_Record_Court
{
	public function getNumberOfStudentsWithGender($gender)
	{
		return parent::getNumberOfStudentsWithGender($gender);
	}
}

class test_unit_A25_Record_Court_GetNumberOfStudentsWithGenderTest extends
		test_Framework_UnitTestCase
{
	private $court;

	public function setUp()
	{
		parent::setUp();
		$this->court = new unit_GetNumberOfStudentsWithGender_A25_Record_Court();
	}

	/**
	 * @test
	 */
	public function returnsZero_whenNoEnrollments()
	{
		$this->expectGenders(0,0);
	}
	/**
	 * @test
	 */
	public function returnsOneMale_whenMaleEnrollment()
	{
		$enroll = new A25_Record_Enroll();
		$student = new A25_Record_Student();
		$student->gender = 'M';
		$enroll->Student = $student;
		$this->court->Enrollments[] = $enroll;
		
		$this->expectGenders(1,0);
	}
	/**
	 * @test
	 */
	public function returnsOneFemale_whenFemaleEnrollment()
	{
		$enroll = new A25_Record_Enroll();
		$student = new A25_Record_Student();
		$student->gender = 'F';
		$enroll->Student = $student;
		$this->court->Enrollments[] = $enroll;

		$this->expectGenders(0,1);
	}

	private function expectGenders($male,$female)
	{
		$this->expect($male,'M');
		$this->expect($female,'F');
	}
	private function expect($expected,$gender)
	{
		$this->assertEquals($expected,
				$this->court->getNumberOfStudentsWithGender($gender));
	}
}