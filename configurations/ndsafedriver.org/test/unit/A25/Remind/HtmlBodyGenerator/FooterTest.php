<?php

class test_unit_A25_Remind_HtmlBodyGenerator_FooterTest extends
		test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function footerMatchesStandardFooterText()
  {
    $footer = new HtmlBodyGeneratorWithFooterExposed();

    $this->assertEquals($this->expectedOutput(), $footer->footer());
  }
  private function expectedOutput()
  {
    return <<<EOD
<p style="text-align: left;">
If you are unable to attend this class, please <a href="https://aliveat25.us/nd/account">cancel or reschedule</a> your class as soon as possible.
<br/><br/>
Cancellation Policy:  If you are unable to attend a training session, you must
cancel at least three business days prior to the course for a full refund.
Late or un-cancelled registrations are non-refundable.  No one will be allowed
into the class once class is in session.</p>
<p style="margin-top: 36px;">
Thank you,<br/>
<br/>
Alive at 25
</p>
</div>
</div>
</body>
</html>
EOD;
  }
}

class HtmlBodyGeneratorWithFooterExposed extends A25_Remind_HtmlBodyGenerator
{
  public function footer() {
    return parent::footer();
  }
}
