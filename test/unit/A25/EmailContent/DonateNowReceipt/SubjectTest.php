<?php

require_once ServerConfig::webRoot . '/plugins/DonateNow.php';

class test_unit_A25_EmailContent_DonateNowReceipt_SubjectTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function sendsSubject()
	{
    $donation = new A25_Record_IndependentDonation();
    $receipt = new DonateNowReceiptWithSubjectExposed($donation);
    
    $expected = A25_EmailContent::wrapSubject('Donation receipt',
        PlatformConfig::agency);
    $this->assertEquals($expected, $receipt->subject());
	}
}

class DonateNowReceiptWithSubjectExposed extends A25_EmailContent_DonateNowReceipt
{
  public function subject() {
    return parent::subject();
  }
}