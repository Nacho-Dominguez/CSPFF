<?php

class test_unit_A25_View_Student_Account_Online_UpcomingCourseMessageTest extends
    test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function hasCorrectInfo()
  {
    $server = A25_DI::ServerConfig();
    $server->onlineCourseUrl = 'urlgoeshere';

    $platform = A25_DI::PlatformConfig();
    $platform->affid = 'affidgoeshere';

    $student = new A25_Record_Student();
    $student->userid = 123;
    $student->zip = 80401;
    $student->first_name = 'Johnny';
    $student->last_name = 'Test';
    $student->email = 'noemail@nomail.com';

    $online = new OnlineWithUpcomingCourseMessageExposed($student);
    $message = $online->upcomingCourseMessage($enroll);
    $expected = '<form method="post" action="urlgoeshere">
      <input type="hidden" name="affid" value="affidgoeshere">
      <input type="hidden" name="ACode" value="acodegoeshere">
      <input type="hidden" name="LoginID" value="123">
      <input type="hidden" name="password" value="80401">
      <input type="hidden" name="fname" value="Johnny">
      <input type="hidden" name="lname" value="Test">
      <input type="hidden" name="email" value="noemail@nomail.com">
      <input type="hidden" name="r_type" value="rtypegoeshere">
      <input type="submit" style="font-size: 12px" value="Click here to access the course">
    </form><p>Provided by:</p><img src="' . ServerConfig::staticHttpUrl() . 'images/cspff_purple.png" style="vertical-align: middle" /> with <img src="' . ServerConfig::staticHttpUrl() . 'images/SafetyServeLogo.gif" style="vertical-align: middle"/>';
    $this->assertEquals($expected, $message);
  }
}

class OnlineWithUpcomingCourseMessageExposed
    extends A25_View_Student_Account_Online
{
  public function upcomingCourseMessage($enroll)
  {
    return parent::upcomingCourseMessage($enroll);
  }
  public function getRType()
  {
    return 'rtypegoeshere';
  }
  public function getACode()
  {
    return 'acodegoeshere';
  }
}
