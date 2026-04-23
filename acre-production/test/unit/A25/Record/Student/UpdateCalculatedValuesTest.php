<?php

class test_unit_A25_Record_Student_UpdateCalculatedValuesTest extends
		test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function setsCalcBalance()
	{
    $student = new StudentStubbingAccountBalance();
    $student->updateCalculatedValues();
    $this->assertEquals(9.00, $student['calc_balance']);
	}
}

class StudentStubbingAccountBalance extends A25_Record_Student
{
  public function getAccountBalance()
  {
    // We are just calling it here to make sure it exists, so that if we rename
    // it later, this test will notice.  But the value it returns has no bearing
    // to our test; we just ignore it.
    parent::getAccountBalance();
    
    return 9.00;
  }
}