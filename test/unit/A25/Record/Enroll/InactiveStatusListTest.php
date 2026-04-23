<?php

/**
 * @todo-active_enroll If an enrollment is "active", it really means that the
 * student would owe tuition.  Verify that no usages of it assume a different
 * meaning, then use a better word than "Active" in all of the methods that
 * are related to Enrollments.
 */
class test_unit_A25_Record_Enroll_InactiveStatusListTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function returnsCorrectList()
  {
    $expected = array(
      A25_Record_Enroll::statusId_canceled,
      A25_Record_Enroll::statusId_noShow,
      A25_Record_Enroll::statusId_kickedOut
    );
    
    $actual = A25_Record_Enroll::inactiveStatusList();
    
    sort($expected);
    sort($actual);
    $this->assertEquals($expected, $actual);
  }
}