<?php

class test_unit_A25_Record_Enroll_AttendedStatusListTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function returnsCorrectList()
  {
    $expected = array(
      A25_Record_Enroll::statusId_completed,
      A25_Record_Enroll::statusId_failed,
      A25_Record_Enroll::statusId_pending,
      A25_Record_Enroll::statusId_unavailable,
    );
    
    $actual = A25_Record_Enroll::attendedStatusList();
    
    sort($expected);
    sort($actual);
    $this->assertEquals($expected, $actual);
  }
}