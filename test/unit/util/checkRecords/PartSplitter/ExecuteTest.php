<?php

class test_unit_util_checkRecords_PartSplitter_ExecuteTest extends test_Framework_UnitTestCase
{
  /**
   * @test 
   */
	public function stopsWhenOffsetEqualsEarliestRecord()
  {
    $total_count = 2;
    $earliest = 1;
    
		$calculator = $this->getMock('CalculatorWithExecuteExposed',
        array('runPart'), array(2, $total_count - $earliest));
    
    $calculator->expects($this->once())->method('runPart')->with($earliest);
    
    $calculator->execute($earliest);
	}
  /**
   * @test 
   */
	public function whenOffsetLessThanEarliestRecord_stopsAndSetsOffsetToEarliestRecord()
  {
    $total_count = 3;
    $earliest = 2;
    
		$calculator = $this->getMock('CalculatorWithExecuteExposed',
        array('runPart'), array($total_count, $total_count - $earliest));
    
    $calculator->expects($this->once())->method('runPart')->with($earliest);
    
    $calculator->execute($earliest - 1);
	}
  /**
   * @test 
   */
	public function decreasesByLimit()
  {
    $total_count = util_checkRecords_PartSplitter::LIMIT * 2;
    
		$calculator = $this->getMock('CalculatorWithExecuteExposed',
        array('runPart'), array($total_count, null));
    
    $calculator->expects($this->exactly(2))->method('runPart');
    
    $calculator->execute(util_checkRecords_PartSplitter::LIMIT);
	}
}

class CalculatorWithExecuteExposed extends util_checkRecords_PartSplitter
{
  public function __construct($total_count,
      $number_of_most_recent_records_to_update)
  {
    return parent::__construct(null, $total_count,
        $number_of_most_recent_records_to_update);
  }
  public function execute($offset) {
    return parent::execute($offset);
  }
}