<?php

class test_unit_A25_Record_Enroll_OccupiesSeatStatusListTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function returnsCorrectList()
  {
    $expected = array(
      A25_Record_Enroll::statusId_registered,
      A25_Record_Enroll::statusId_student,
      A25_Record_Enroll::statusId_completed,
      A25_Record_Enroll::statusId_unavailable,
      A25_Record_Enroll::statusId_pending,
      A25_Record_Enroll::statusId_failed,
    );
    
    $actual = A25_Record_Enroll::occupiesSeatStatusList();
    
    sort($expected);
    sort($actual);
    $this->assertEquals($expected, $actual);
  }
}