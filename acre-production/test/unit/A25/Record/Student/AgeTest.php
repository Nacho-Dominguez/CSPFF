<?php

class test_unit_A25_Record_Student_AgeTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function theSameSecond_LeapYear()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2000-01-01';
		$this->assertEquals(1,$student->age(strtotime('2001-01-01')));
	}
	/**
	 * @test
	 */
	public function theSecondBefore_LeapYear()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2000-01-01';
		$this->assertEquals(0,$student->age(strtotime('2000-12-31 23:59:59')));
	}
	/**
	 * @test
	 */
	public function theSameSecond_NonLeapYear()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2001-01-01';
		$this->assertEquals(1,$student->age(strtotime('2002-01-01')));
	}
	/**
	 * @test
	 */
	public function theSecondBefore_NonLeapYear()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2001-01-02';
		$this->assertEquals(0,$student->age(strtotime('2002-01-01 23:59:59')));
	}
	/**
	 * @test
	 */
	public function worksWithDifferentMonths_before()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2001-03-01';
		$this->assertEquals(0,$student->age(strtotime('2002-02-03')));
	}
	/**
	 * @test
	 */
	public function worksWithDifferentMonths_after()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2001-03-01';
		$this->assertEquals(1,$student->age(strtotime('2002-04-01')));
	}
	/**
	 * @test
	 */
	public function worksWithSameMonth_dateBefore()
	{
		$student = new A25_Record_Student();
		$student->date_of_birth = '2001-03-06';
		$this->assertEquals(0,$student->age(strtotime('2002-03-05')));
	}
}
?>
