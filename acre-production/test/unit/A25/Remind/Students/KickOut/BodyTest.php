<?php

class test_unit_A25_Remind_Students_KickOut_BodyTest extends
test_Framework_UnitTestCase
{
  private $body;
  private $enroll;
  
  private function setUpTests()
  {
    $this->body = new KickOutWithBodyExposed();
    
    $course = new A25_Record_Course();
    $course->course_start_date = '2012-11-30';
    $course->register_cc_days = 15;
    
    $student = new A25_Record_Student();
    
    $this->enroll = new A25_Record_Enroll();
    $this->enroll->Course = $course;
    $this->enroll->Student = $student;
  }
  /**
   * @test
   */
  public function enrolledAfterPaymentOptionDeadline()
  {
    $this->setUpTests();
    $this->enroll->date_registered = '2012-11-16';
    
    $generator = $this->getMock('A25_Remind_HtmlBodyGenerator',
        array('header'));
    $generator->expects($this->any())->method('header')
        ->will($this->returnValue('Header goes here'));
    
    $factory = $this->getMock('A25_Factory_PhysicalLocation', array('HtmlBodyGenerator'));
    $factory->expects($this->any())->method('HtmlBodyGenerator')
        ->will($this->returnValue($generator));
    
    A25_DI::setFactory($factory);
    
    $this->assertEquals($this->expectedOutput(), $this->body->body($this->enroll));
  }
  
  /**
   * @test
   */
  public function enrolledBeforePaymentOptionDeadline()
  {
    $this->setUpTests();
    $this->enroll->date_registered = '2012-11-14';
    
    $expected = '</p>
<p>
If you have already mailed in payment, please call our office at
(720) 269-4046.
</p>
<p style="margin-top: 36px;">';
    
    $this->assertContains($expected, $this->body->body($this->enroll));
  }
  
  private function expectedOutput()
  {
    return <<<END
Header goes here<p>
Your seat reservation has expired for the Alive at 25 Driver's Awareness 
course on Friday, November 30 
because payment has not been received.  You may register again for the same
course or a different course at
<a href="www.aliveat25.us">
www.aliveat25.us</a>.  Please be sure to
submit payment in time to preserve your seat in the course.
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
  }
}

class KickOutWithBodyExposed extends A25_Remind_Students_KickOut
{
  public function body(A25_Record_Enroll $enroll) {
    return parent::body($enroll);
  }
}
