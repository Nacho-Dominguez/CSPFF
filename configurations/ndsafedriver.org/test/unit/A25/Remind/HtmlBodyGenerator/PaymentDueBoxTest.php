<?php

class test_unit_A25_Remind_HtmlBodyGenerator_PaymentDueBoxTest extends
		test_Framework_UnitTestCase
{
  /**
   * @todo-jon-low-small - These first two tests are really testing
   * SlowPaymentInstructions.  Unit test SlowPaymentInstructions by itself and
   * consolidate these tests.
   */
  /**
   * @test
   */
  public function fillsInBodyWithoutChecksOrKickOut()
  {
    $payment = new HtmlBodyGeneratorWithPaymentDueBoxExposed();

    $enroll = new A25_Record_Enroll();
    $student = $this->getMock('A25_Record_Student', array('getAccountBalance'));
    $student->expects($this->any())->method('getAccountBalance')
        ->will($this->returnValue(79));
    $enroll->Student = $student;
    $student->student_id = 123;

    $enroll->date_registered = '2012-11-01 08:00';

    $course = new A25_Record_Course();
    $enroll->Course = $course;
    $course->course_start_date = '2012-12-31 08:00';
    $course->late_fee_deadline = 48;
    $course->late_fee = 10;

    $this->assertEquals($this->oldExpectedOutput(), $payment->paymentDueBox($student, $course, $enroll));
  }
  private function oldExpectedOutput()
  {
    return <<<EOD
<div style="width: 80%; text-align: left;
font-size: 12px; background-color: #ffefdf; color: #333;
margin: 36px; padding: 12px;">
<h3 style="text-align: center; color: #000;">Amount due: $79</h3>
<h4><a href="https://aliveat25.us/nd/account">Pay now via credit card online</a></h4>
<p>
Or, mail check or money order payable to "Alive at 25"
to:
</p>
<p>
Alive At 25<br/>1640 Burnt Boat Dr.<br/>Bismarck, ND 58503</p>
<p>
Please make sure payment arrives before 8:00 am on Saturday, December 29 to
avoid a late payment fee of $10.  To ensure your certificate of
completion will be pre-printed and available at the class we must receive
payment prior to noon one week prior to the class date.  <br/><br/>Please include the student's name, Alive at 25 student ID number (#123) and the city and date of class attending with your payment.</p>
<p style="font-size: 10px; color: #999;">
If your check or money order is not approved by your financial institution, a
$10 late fee and a
$35 NSF charge will apply.
</p>
<p style="font-size: 10px; color: #999;">
All payments are non-refundable and cannot be transferred to another student's
account.  However, if you cancel your enrollment and re-enroll in a different
class, your payment will automatically be applied to the new enrollment.
</p>
<p style="font-style: italic">
If you have already mailed in payment, please disregard this message.
</p>
</div>

EOD;
  }
}

class HtmlBodyGeneratorWithPaymentDueBoxExposed extends A25_Remind_HtmlBodyGenerator
{
  public function paymentDueBox($student, $course, $enroll) {
    return parent::paymentDueBox($student, $course, $enroll);
  }
}
