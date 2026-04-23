<?php

class WhenHasCourtSurcharge_DisplaysSurchargeMessage_ListPayOpts extends
		A25_ListPayOpts
{
	public function shouldDisplaySurcharge()
	{
		return true;
	}
	public function shouldDisplayLateFee()
	{
		return false;
	}
}
class WhenHasLateFee_DisplaysLateFeeMessage_ListPayOpts extends
		A25_ListPayOpts
{
	public function shouldDisplaySurcharge()
	{
		return false;
	}
	public function shouldDisplayLateFee()
	{
		return true;
	}
}

class test_unit_A25_Template_ListPayOpts_FooterTest extends test_Framework_UnitTestCase
{
	private $balance = 20;
	private $order;

	/**
	 * @test
	 */
	public function WhenNoSurchargeOrLateFee_DoesNotDisplayMessages()
	{
		$order = new A25_Record_Order();
    $course = new A25_Record_Course();
		
		$template = new A25_ListPayOpts($this->balance,
				$order, $course);
		$this->assertEqualsIgnoringWhitespace(
    $this->expectedWithoutSurchargeMessage($template),
    $template->footer());
	}

	private function expectedWithoutSurchargeMessage($template)
	{
		if( !$template->shouldDisplaySurcharge())
			return '</div>';
	}

	/**
	 * @test
	 */
	public function WhenHasCourtSurcharge_DisplaysSurchargeMessage()
	{
		$this->order = new A25_Record_Order();
    $course = new A25_Record_Course();

		$template = new
				WhenHasCourtSurcharge_DisplaysSurchargeMessage_ListPayOpts(
					$this->balance, $this->order, $course);
		$this->assertEqualsIgnoringWhitespace(
				$this->expectedWithSurchargeMessage($template),
				$template->footer());
	}

	/**
	 * @test
	 */
	public function WhenHasLateFee_DisplaysLateFeeMessage()
	{
		$order = new A25_Record_Order();
    $course = new A25_Record_Course();

		$template = new
				WhenHasLateFee_DisplaysLateFeeMessage_ListPayOpts(
					$this->balance, $order, $course);
		$this->assertEqualsIgnoringWhitespace(
				$this->expectedWithLateFeeMessage(),
				$template->footer());
	}

	private function expectedWithSurchargeMessage($template)
	{
		if( !$template->shouldDisplaySurcharge())
			return '</div></div>';
		$return = '<div style="border: 1px solid #BBBBBB; background-color: #efefef;
padding: 1em; margin: 1em;
">
<p>** About the <i>DOR Fee</i> &mdash; Colorado Revised Statute 42-4-1717, requires defendants who have
violated traffic laws and who agree to or are ordered by a court to attend a
driver improvement school/course, to pay a $' . $this->order->courtSurchargeAmount()
				. ' penalty surcharge.  This
surcharge is collected by the driver improvement school and is remitted in full
to the Colorado Department of Revenue.  The funds generated through the
collection of the penalty surcharge are used by the Colorado Department of
Revenue to underwrite the administrative costs associated with a driver
improvement school quality control program established by this statute.  Driver
improvement schools do not retain any part of the surcharge.
</p>
</div>
</div>';
		return $return;
	}
  private function expectedWithLateFeeMessage()
  {
    return '<div style="border: 1px solid #BBBBBB; background-color: #efefef;
padding: 1em; margin: 1em; ">
<p>* A late fee of $0 applies to any payment that occurs within 0 hours of the course or later.</p>
</div>
</div>';
  }
}
?>
