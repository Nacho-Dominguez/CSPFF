<?php

class test_unit_A25_Remind_HtmlBodyGenerator_HeaderTest extends
		test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function headerMatchesStandardHeaderText()
  {
    $header = new HtmlBodyGeneratorWithHeaderExposed();

    $enroll = new A25_Record_Enroll();
    $student = $this->getMock('A25_Record_Student', array('getAccountBalance'));
    $student->expects($this->any())->method('getAccountBalance')
        ->will($this->returnValue(79));
    $enroll->Student = $student;
    $student->first_name = 'John';

    $title = 'Subject goes here';

    $this->assertEquals($this->expectedOutput(), $header->header($student, $title));
  }
  private function expectedOutput()
  {
    return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Subject goes here</title>
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
';
  }
}

class HtmlBodyGeneratorWithHeaderExposed extends A25_Remind_HtmlBodyGenerator
{
  public function header($student, $title) {
    return parent::header($student, $title);
  }
}
