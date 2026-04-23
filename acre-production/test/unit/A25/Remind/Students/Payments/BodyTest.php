<?php

/**
 *@todo-jon-low-small - change this test so it isn't affected by changes in the
 * header and footer.  Those are tested separately.
 */

/**
 * This test makes use of A25_Remind_Students_Payments_SecondReminder in order
 * to have a concrete class, but it is actually testing body() in
 * A25_Remind_Students_Payments
 */
class test_unit_A25_Remind_Students_Payments_BodyTest extends
		test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function fillsInBody()
  {
    $payment = new PaymentsWithBodyExposed();

    $enroll = new A25_Record_Enroll();
    $student = $this->getMock('A25_Record_Student', array('getAccountBalance'));
    $student->expects($this->any())->method('getAccountBalance')
        ->will($this->returnValue(79));
    $enroll->Student = $student;
    $student->first_name = 'John';

    $course = new A25_Record_Course();
    $enroll->Course = $course;
    $course->course_start_date = '2012-12-31 08:00';
    $course->late_fee_deadline = 48;

    $this->assertEquals($this->expectedOutput($student, $course, $enroll), $payment->body($enroll));
  }

  private function expectedOutput($student, $course, $enroll)
  {
    $generator = new A25_Remind_HtmlBodyGenerator();
    $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Payment reminder from Alive at 25</title>
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
Thank you for registering for an Alive at 25 Driver\'s Awareness Course
on Monday, December 31.
This is just a friendly reminder that
payment is due.
</p>
';
    $output .= $generator->paymentDueBox($student, $course, $enroll);
    $output .= <<<END
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
</html>
END;
    return $output;
  }
}

class PaymentsWithBodyExposed
    extends A25_Remind_Students_Payments_SecondReminder
{
  public function body(A25_Record_Enroll $enroll) {
    return parent::body($enroll);
  }
}
