<?php

class test_unit_A25_Remind_Students_UpcomingClass_SubjectTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function sendsSubject()
	{
    $reminder = new UpcomingClassSubjectWithMethodsExposed();

    $expected = A25_EmailContent::wrapSubject('Class reminder');
    $this->assertEquals($expected, $reminder->subject());
	}
}

class UpcomingClassSubjectWithMethodsExposed extends A25_Remind_Students_UpcomingClass_FirstReminder
{
  public function subject() {
    return parent::subject();
  }
}
