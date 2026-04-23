<?php

class test_unit_A25_Envelope_BodyTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function fillsInBody()
	{
    $content = new EnvelopeWithBodyExposed(new EmailContentForTest());

    $this->assertEquals($this->expectedOutput(), $content->body());
	}

  private function expectedOutput()
  {
    $content = new EmailContentForTest();
    $output = <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>
END;
    $output .= $content->subject();
    $output .= '</title>
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
(720) 269-4046</div>
<div style="clear: both"></div>
</div>
<div style="margin: 12px;">
<p style="margin-top: 36px;">
';
    $output .= '  ' . $content->innerHtml();
    $output .= <<<END
</p>
</div>
</div>
</body>
</html>

END;
    return $output;
  }
}

class EnvelopeWithBodyExposed extends A25_Envelope
{
  public function body() {
    return parent::body();
  }
}

class EmailContentForTest extends A25_EmailContent
{
  public function subject() {
    return 'Subject goes here';
  }
  public function innerHtml() {
    return 'Inner HTML goes here';
  }
}
