<?php

require_once ServerConfig::webRoot . '/plugins/DonateNow.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Listing_Donations_FormatRowTest extends
    test_Framework_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/DonateNow');
	}
	/**
	 * @test
	 */
	public function returnsExpectedArray()
	{
    $donation = new A25_Record_IndependentDonation();
    $donation->reason = 2;
    $donation->created = '2012-11-01 01:00:00';
    $donation->amount = '3.00';
    $donation->pay_type_id = 3;
    $donation->benefactor = 'Jane Doe';
    $donation->defendant = 'John Smith';
    $donation->cc_trans_id = 12345;
    $donation->court_id = 321;
    
    $court = new A25_Record_Court();
    $court->court_id = 321;
    $court->court_name = 'Supreme Pizza Court';
    
    $donation->Court = $court;

		$expectedArray = array(
      'Date' => $donation->created,
      'Benefactor' => $donation->benefactor,
      'Donation Type' => 'License Plate',
      'Amount' => $donation->amount,
      'Pay Type' => 'Credit Card',
      'Defendant' => $donation->defendant,
      'Court Name' => $donation->courtName(),
      'Credit Card Transaction #' => $donation->cc_trans_id,
      'View Receipt' => '<a href="' . A25_Link::to(
					'/administrator/donation-receipt?id=' . $donation->id)
				. '">View Receipt</a>'
		);
		$listing = new Listing_DonationsWithFormatRowExposed($limit, $offset);
		$this->assertEquals($expectedArray, $listing->formatRow($donation));
	}
}

class Listing_DonationsWithFormatRowExposed extends A25_Report_Donations
{
  public function formatRow(A25_DoctrineRecord $donation)
  {
    return parent::formatRow($donation);
  }
}
