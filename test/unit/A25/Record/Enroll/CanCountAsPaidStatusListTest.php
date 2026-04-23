<?php

class test_unit_A25_Record_Enroll_CanCountAsPaidStatusListTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function returnsCorrectList()
  {
    $expected = array(
      A25_Record_Enroll::statusId_student,
      A25_Record_Enroll::statusId_completed,
      A25_Record_Enroll::statusId_unavailable,
      A25_Record_Enroll::statusId_failed,
      A25_Record_Enroll::statusId_noShow
    );
    
    $actual = A25_Record_Enroll::canCountAsPaidStatusList();
    
    sort($expected);
    sort($actual);
    $this->assertEquals($expected, $actual);
  }
}