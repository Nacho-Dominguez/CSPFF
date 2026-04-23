<?php
require_once(dirname(__FILE__) . '/../../../../../autoload.php');

class OnlyTuition_Order extends A25_Record_Order
{
	public function tuitionFee()
	{
		return 22;
	}
}
class WithSurcharge_Order extends OnlyTuition_Order
{
	public function courtSurchargeAmount()
	{
		return 67;
	}
}
class WithLateFee_Order extends OnlyTuition_Order
{
	public function hasLateFee()
	{
		return true;
	}
	public function lateFee()
	{
		return 68;
	}
}

class test_unit_A25_Template_ListPayOpts_OrderSummaryTest extends
		test_Framework_UnitTestCase
{
	private $_balance = 22;
	private $_order;

	/**
	 * @test
	 */
	public function OnlyTuition()
	{
		$this->_order = new OnlyTuition_Order();

		$this->shouldReturnTuitionAnd('');
	}
	/**
	 * @test
	 */
	public function WithSurcharge()
	{
		$this->_order = new WithSurcharge_Order();
		$this->_balance += $this->_order->courtSurchargeAmount();

		$this->shouldReturnTuitionAnd('<tr><td>DOR Fee** &nbsp;<span style="font-size: smaller; '
				. 'font-style: italic;">(If you received a waiver form from '
				. 'the court, <a href="waive-surcharge?item_id=' . $surcharge->item_id
        . '">click here</a>.)</span></td><td align="right">$'
				. $this->_order->courtSurchargeAmount() . '</td></tr>');
	}
	/**
	 * @test
	 */
	public function WithLateFee()
	{
		$this->_order = new WithLateFee_Order();
		$this->_balance += $this->_order->lateFee();

		$this->shouldReturnTuitionAnd('<tr><td>Late Fee*</td><td align="right">$'
				. $this->_order->lateFee() . '</td></tr>');
	}

	/**
	 * @test
	 */
	public function WithCredit()
	{
		$this->_order = new OnlyTuition_Order();
		$this->_balance -= 10;

		$this->shouldReturnTuitionAnd(
				'<tr><td>Credits from previous payments</td><td align="right">(-$'
				. 10 . ')</td></tr>');
	}
	/**
	 * @test
	 */
	public function WithPreviouslyOwed()
	{
		$this->_order = new OnlyTuition_Order();
		$this->_balance += 10;

		$this->shouldReturnTuitionAnd(
				'<tr><td>Unpaid amount owed from previous classes</td><td align="right">$'
				. 10 . '</td></tr>');
	}

	private function shouldReturnTuitionAnd($expected)
	{
		$template = new A25_ListPayOpts($this->_balance,
				$this->_order, new A25_Record_Course());

		$this->assertEqualsIgnoringWhitespace(
				$this->expectedInside($expected),
				$template->orderSummary());
	}

	private function expectedInside($inside)
	{
		$return .= '<table id="orderSummary" cellspacing="0" cellpadding="2"
			style="font-size: larger;
width: 100%">
<td colspan="2" style="border-bottom: 1px solid black;
font-weight: bold;
">Order Summary</td>
<tr><td>Tuition</td><td align="right">$'
		. $this->_order->tuitionFee() . '</td></tr>
			' . $inside . '
<tr style="color: #BB2222; font-weight: bold;"><td style="border-top: 
1px solid black;">Payment Due</td><td style="border-top: 1px solid black;" align="right">$<span id="paymentDue">'
		. $this->_balance . '</span></td></tr>
</table>';
		return $return;
	}
}

?>
