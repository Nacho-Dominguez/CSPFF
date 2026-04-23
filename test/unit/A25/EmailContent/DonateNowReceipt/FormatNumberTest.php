<?php

require_once ServerConfig::webRoot . '/plugins/DonateNow.php';

class test_unit_A25_EmailContent_DonateNowReceipt_FormatNumberTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function addsDecimalPlaces()
	{
    $donation = new A25_Record_IndependentDonation;
    $donation->amount = 3;
    $receipt = new DonateNowReceiptWithFormatNumberExposed($donation->amount);
    $this->assertTrue($receipt->formatNumber() === '3.00');
	}
  /**
	 * @test
	 */
	public function doesntAddDecimalPlacesIfAlreadyThere()
	{
    $donation = new A25_Record_IndependentDonation;
    $donation->amount = 3.00;
    $receipt = new DonateNowReceiptWithFormatNumberExposed($donation->amount);
    $this->assertTrue($receipt->formatNumber() === '3.00');
	}
  /**
	 * @test
	 */
	public function RemovesDecimalPlacesIfTooMany()
	{
    $donation = new A25_Record_IndependentDonation;
    $donation->amount = 3.0000;
    $receipt = new DonateNowReceiptWithFormatNumberExposed($donation->amount);
    $this->assertTrue($receipt->formatNumber() === '3.00');
	}
}

class DonateNowReceiptWithFormatNumberExposed extends A25_EmailContent_DonateNowReceipt
{
  public function formatNumber() {
    return parent::formatNumber();
  }
}