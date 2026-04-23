<?php

class test_unit_A25_View_Student_Account_Online_GetRTypeTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function withoutAccount()
  {
    $student = new A25_Record_Student;
    $online = $this->getMock('OnlineWithGetRTypeExposed',
        array('doesAccountExist'), array($student));
    $online->expects($this->once())->method('doesAccountExist')
        ->will($this->returnValue(false));
    $rtype = $online->getRType();
    $this->assertEquals('CRE', $rtype);
  }
  /**
   * @test
   */
  public function withAccount()
  {
    $student = new A25_Record_Student;
    $online = $this->getMock('OnlineWithGetRTypeExposed',
        array('doesAccountExist'), array($student));
    $online->expects($this->once())->method('doesAccountExist')
        ->will($this->returnValue(true));
    $rtype = $online->getRType();
    $this->assertEquals('LG', $rtype);
  }
}

class OnlineWithGetRTypeExposed extends A25_View_Student_Account_Online
{
  public function getRType()
  {
    return parent::getRType();
  }
  public function doesAccountExist()
  {
  }
}
