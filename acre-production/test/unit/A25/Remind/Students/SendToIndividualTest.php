<?php

class test_unit_A25_Remind_Students_SendToIndividualTest extends
		test_Framework_UnitTestCase
/**
 * This test makes use of A25_Remind_Students_UpcomingClass in order to have a
 * concrete class, but it is actually testing sendToIndividual() in
 * A25_Remind_Students
 */
{
  /**
   * @var PHPUnit_Framework_MockObject_MockObject
   */
  private $enroll;
  /**
   * @var PHPUnit_Framework_MockObject_MockObject
   */
  private $reminder;
  /**
   * @var A25_Record_Student
   */
  private $student;
  /**
   * @var A25_StudentMailer
   */
  private $student_mailer;

  public function setUp()
  {
    parent::setUp();

    $this->enroll = $this->getMock('A25_Record_Enroll', array('save'));
    $this->student = new A25_Record_Student();
    $this->enroll->Student = $this->student;

    $this->student_mailer = $this->mock('A25_StudentMailer');

    $factory = $this->mock('A25_Factory');
    $factory->expects($this->any())->method('StudentMailer')
        ->will($this->returnValue($this->student_mailer));

    A25_DI::setFactory($factory);

		$this->reminder = $this->getMock('StudentsWithMethodsExposed',
        array('body', 'alt_body'));
  }

  private function execute()
  {
		$this->reminder->sendToIndividual($this->enroll);
  }

	/**
	 * @test
	 */
	public function sendsToStudent()
	{
    $this->student_mailer->expects($this->once())->method('send')
        ->with($this->student, $this->anything());

    $this->execute();
	}

  /**
   * @test
   */
  public function sendsHtmlBody()
  {
    $body = '<html>This is the body</html>';
    $this->reminder->expects($this->once())->method('body')
        ->with($this->enroll)
        ->will($this->returnValue($body));

    $this->student_mailer->expects($this->once())->method('send')
        ->with($this->anything(), $this->anything(), $body, $this->anything());

    $this->execute();
  }

  /**
   * @test
   */
  public function sendsAlternateTextBody()
  {
    $alt_body = 'This is the body';
    $this->reminder->expects($this->once())->method('alt_body')
        ->with($this->enroll)
        ->will($this->returnValue($alt_body));

    $this->student_mailer->expects($this->once())->method('send')
        ->with($this->anything(), $this->anything(), $this->anything(), true,
            $alt_body);

    $this->execute();
  }
}

class StudentsWithMethodsExposed extends A25_Remind_Students_UpcomingClass_FirstReminder
{
  public function sendToIndividual($enroll) {
    return parent::sendToIndividual($enroll);
  }
}
