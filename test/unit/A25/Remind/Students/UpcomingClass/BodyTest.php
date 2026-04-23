<?php

/**
 *@todo-jon-low-small - change this test so it isn't affected by changes in the
 * header and footer.  Those are tested separately.
 */

class test_unit_A25_Remind_Students_UpcomingClass_BodyTest extends
		test_Framework_UnitTestCase
{
  private $class;
  private $enroll;
  private $student;
  private $course;

  private function setUpTests($balance)
  {
    $this->class = new UpcomingClassWithBodyExposed();

    $this->enroll = new A25_Record_Enroll();
    $this->student = $this->getMock('A25_Record_Student', array('getAccountBalance'));
    $this->student->expects($this->any())->method('getAccountBalance')
        ->will($this->returnValue($balance));
    $this->student->first_name = 'John';
    $this->enroll->Student = $this->student;

    $this->course = new A25_Record_Course();
    $this->enroll->Course = $this->course;
    $this->course->course_start_date = '2012-12-31 08:00';
    $this->course->duration = '09:00:00';
    $location = new A25_Record_Location();
    $this->enroll->Course->Location = $location;
  }
  /**
   * @test
   */
  public function fillsInBodyWithoutPaymentDueBoxWhenNoPaymentIsDue()
  {
    $this->setUpTests('0');

    $this->assertEquals($this->expectedOutput(), $this->class->body($this->enroll));
  }

  /**
   * @test
   */
  public function fillsInPaymentDueBoxWhenPaymentIsDue()
  {
    $this->setUpTests('79');

    $generator = new A25_Remind_HtmlBodyGenerator();
    $expected = $generator->paymentDueBox($this->student, $this->course, $this->enroll);

    $this->assertContains($expected, $this->class->body($this->enroll));
  }

  private function expectedOutput()
  {
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Class reminder from Alive at 25</title>
</head>
<body>
<div style="width: 100%; font-family: helvetica,arial,sans-serif;
color: #333; font-size: 14px;">
<div style="width: 100%; border-bottom: 1px solid #ccc">
<img style="margin: 10px" alt="Alive at 25"
src="' . ServerConfig::staticHttpUrl() . 'images/logo.gif" />
<div style="float: right; text-align: right;
margin: 10px; margin-top: 24px;">
www.aliveat25.us<br/>
(720) 269-4046<br/>
<a href="https://aliveat25.us/co/account">Manage your account online</a>
</div>
<div style="clear: both"></div>
</div>
<div style="margin: 12px;">
<p style="margin-top: 36px;">
John,
</p>

<p>
Just a friendly reminder about your upcoming Alive at 25 Driver\'s Awareness Course.
</p>
<div style="margin-top: 24px; margin-bottom: 24px;">
<div style="width: 90%; max-width: 300px; background-color: #f2f2f2;
text-align: center; border: 0px solid #999; color: #333;
padding: 12px; margin-right: 28px; margin-left: 28px; float: right;">
<b>Monday, December 31, 2012</b><br/>8:00 am &ndash; 5:00 pm<br/>
<p><br/><br/>,  </p>
<p>
<img src="' . ServerConfig::staticHttpUrl() . 'images/pointer_icon_small.png"
style="vertical-align:middle" alt="pointer"/>
<a href="">Google Maps</a>
</p></div>
<div>
<p style="text-align: left;">
Please <b>bring a photo ID</b> (if available). Be sure to <b>arrive early</b>, as
<em>late arrivals are not allowed to attend</em>.
</p>
<p style="text-align: left;">
</p>
</div>
</div>
<p style="text-align: left;">
If you are unable to attend this class, please <a href="https://aliveat25.us/co/account">cancel or
reschedule</a> your class as soon as possible or at least 24 hours in
advance.
</p>
<p style="margin-top: 36px;">
Thank you,<br/>
<br/>
Alive at 25
</p>
</div>
</div>
</body>
</html>';
  }
}

class UpcomingClassWithBodyExposed extends A25_Remind_Students_UpcomingClass_FirstReminder
{
  public function body(A25_Record_Enroll $enroll) {
    return parent::body($enroll);
  }
}
