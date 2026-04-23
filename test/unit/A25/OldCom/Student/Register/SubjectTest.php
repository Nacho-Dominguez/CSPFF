<?php

class test_unit_A25_OldCom_Student_Register_SubjectTest extends
		test_Framework_UnitTestCase
{ 
  /**
	 * @test
	 */
	public function sendsSubject()
	{
    $register = new RegisterWithSubjectExposed();
    
    $expected = A25_EmailContent::wrapSubject('Registration Confirmation');
    $this->assertEquals($expected, $register->subject());
	}
}

class RegisterWithSubjectExposed extends A25_OldCom_Student_Register
{
  public function subject() {
    return parent::subject();
  }
}