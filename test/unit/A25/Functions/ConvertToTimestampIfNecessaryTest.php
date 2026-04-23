<?php

class test_unit_A25_Functions_ConvertToTimestampIfNecessaryTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function convertsIfNotTimestamp()
  {
    $date = '2013-07-01';
    $this->assertEquals('1372658400',
        A25_Functions::convertToTimestampIfNecessary($date));
  }
  
  /**
   * @test
   */
  public function doesNotConvertIfTimestampString()
  {
    $date = '1372658400';
    $this->assertEquals('1372658400',
        A25_Functions::convertToTimestampIfNecessary($date));
  }
  
  /**
   * @test
   */
  public function doesNotConvertIfTimestampInteger()
  {
    $date = 1372658400;
    $this->assertEquals('1372658400',
        A25_Functions::convertToTimestampIfNecessary($date));
  }
}
