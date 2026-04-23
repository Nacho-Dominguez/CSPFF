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
EOD;
  }
}

class HtmlBodyGeneratorWithFooterExposed extends A25_Remind_HtmlBodyGenerator
{
  public function footer() {
    return parent::footer();
  }
}
