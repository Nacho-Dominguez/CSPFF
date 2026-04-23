<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_Donation_DonationAmountTest extends
		test_Framework_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/Donation');
	}

	/**
	 * @test
	 */
	public function returnsAmountIfDonation()
	{
		$donation = new DonationWithDonationAmountExposed();
    $_POST['donation_amount'] = '5';
		
		$expected = 5;
		$result = $donation->donationAmount();
		
		$this->assertEquals($expected, $result);
	}

	/**
	 * @test
	 */
	public function returnsZeroIfZeroDonation()
	{
		$donation = new DonationWithDonationAmountExposed();
    $_POST['donation_amount'] = '0';
		
		$expected = 0;
		$result = $donation->donationAmount();
		
		$this->assertEquals($expected, $result);
	}
  
	/**
	 * @test
	 */
	public function returnsZeroIfNoDonation()
	{
		$donation = new DonationWithDonationAmountExposed();
		
		$expected = 0;
		$result = $donation->donationAmount();
		
		$this->assertEquals($expected, $result);
	}
  
	/**
	 * @test
	 */
	public function returnsZeroIfNegativeDonation()
	{
		$donation = new DonationWithDonationAmountExposed();
    $_POST['donation_amount'] = '-5';
		
		$expected = 0;
		$result = $donation->donationAmount();
		
		$this->assertEquals($expected, $result);
	}
  
	/**
	 * @test
	 */
	public function returnsZeroIfText()
	{
		$donation = new DonationWithDonationAmountExposed();
    $_POST['donation_amount'] = 'Hello';
		
		$expected = 0;
		$result = $donation->donationAmount();
		
		$this->assertEquals($expected, $result);
	}
}

class DonationWithDonationAmountExposed extends A25_Plugin_Donation
{
  public function donationAmount() {
    return parent::donationAmount();
  }
}
