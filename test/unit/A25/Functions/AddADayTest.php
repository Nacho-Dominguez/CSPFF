<?php

class test_unit_A25_Functions_AddADayTest extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function duringDaylightSavings()
  {
    $date = '2013-07-01';
    $this->assertEquals('2013-07-02', A25_Functions::addADay($date));
  }
  
  /**
   * @test
   */
  public function endOfYear()
  {
    $date = '2013-12-31';
    $this->assertEquals('2014-01-01', A25_Functions::addADay($date));
  }
  
  /**
   * @test
   */
  public function beginDaylightSavings()
  {
    $date = '2013-03-10';
    $this->assertEquals('2013-03-11', A25_Functions::addADay($date));
  }
  
  /**
   * @test
   */
  public function endDaylightSavings()
  {
    $date = '2013-11-03';
    $this->assertEquals('2013-11-04', A25_Functions::addADay($date));
  }
}
