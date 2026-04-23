<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class test_unit_A25_Record_Course_GetCourseCompletedEmailBodyTest
		extends test_Framework_UnitTestCase
{
	/**
	 * @test
	 */
	public function getsEmailBody()
	{
    $course = $this->getMock('A25_Record_Course', array('getSetting'));
    $course->expects($this->once())->method('getSetting')
        ->will($this->returnValue('Email text goes here'));
    $email = $course->getCourseCompletedEmailBody();
    $this->assertEquals($this->expectedText(), $email);
	}
  
	/**
	 * @test
	 */
	public function replacesUrl()
	{
    $course = $this->getMock('A25_Record_Course', array('getSetting'));
    $course->expects($this->once())->method('getSetting')
        ->will($this->returnValue('!URL! goes here'));
    $email = $course->getCourseCompletedEmailBody();
    $this->assertEquals($this->expectedUrl(), $email);
	}
  
  private function expectedText()
  {
    return "Email text goes here";
  }
  
  private function expectedUrl()
  {
    return rtrim(ServerConfig::staticHttpsUrl(),'/') . " goes here";
  }
}
