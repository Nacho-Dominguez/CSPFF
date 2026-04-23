<?php

/**
 * This test makes use of A25_Remind_Students_Payments_SecondReminder in order
 * to have a concrete class, but it is actually testing subject() in
 * A25_Remind_Students_Payments
 */
class test_unit_A25_Remind_Students_Payments_SubjectTest extends
		test_Framework_UnitTestCase
{ 
  /**
	 * @test
	 */
	public function sendsSubject()
	{
    $reminder = new PaymentsSubjectWithMethodsExposed();
    
    $expected = A25_EmailContent::wrapSubject('Payment reminder');
    $this->assertEquals($expected, $reminder->subject());
	}
}

class PaymentsSubjectWithMethodsExposed
    extends A25_Remind_Students_Payments_SecondReminder
{
  public function subject() {
    return parent::subject();
  }
}