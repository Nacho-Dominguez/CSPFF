<?php

class test_unit_util_checkRecords_PartSplitter_EarliestRecordToUpdateTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test 
   */
	public function subtractsFromTotalCount()
  {
		$this->assertEquals(9,
      CalculatorExposingEarliestRecordToUpdate::earliestRecordToUpdate(1, 10));
	}
  
  /**
   * @test 
   */
	public function whenNumberToGoBackIsNull_returns0()
  {
		$this->assertEquals(0,
      CalculatorExposingEarliestRecordToUpdate::earliestRecordToUpdate(null, 10));
	}
  
  /**
   * @test 
   */
	public function whenNegative_returns0()
  {
		$this->assertEquals(0,
      CalculatorExposingEarliestRecordToUpdate::earliestRecordToUpdate(11, 10));
	}
}

class CalculatorExposingEarliestRecordToUpdate extends util_checkRecords_PartSplitter
{
  public static function earliestRecordToUpdate(
      $number_of_most_recent_records_to_update, $total_count)
  {
    return parent::earliestRecordToUpdate(
        $number_of_most_recent_records_to_update, $total_count);
  }
}
