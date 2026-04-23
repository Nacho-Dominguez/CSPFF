<?php

require_once ServerConfig::webRoot . '/plugins/DonateNow.php';

class test_unit_A25_EmailContent_DonateNowReceipt_ReceiptTextTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function fillsInDonationAmount()
	{
    $donation = new A25_Record_IndependentDonation();
    $donation->amount = 3;
    $donation->created = '2012-10-31 12:00:00';
    $donation->benefactor = 'John Smith';
    $receipt = new A25_EmailContent_DonateNowReceipt($donation->amount,
        $donation->created, A25_Record_IndependentDonation::reason_None, $donation->benefactor);

    ob_start();
    $receipt->innerHtml();
    $result = ob_get_clean();

    $this->assertEquals($this->expectedOutput(), $result);
	}

  private function expectedOutput()
  {
    $date = date('Y-m-d');
    $output = <<<END
      <h3>Thank you for your gift.</h3>
      <p>
        <span style="margin-right: 20px;">
          Date: October 31, 2012        </span>
        Amount: $3.00      </p>
      <p>
      Donor: John Smith
      </p>      <p>
      Thank you for your donation to the <b>Colorado State Patrol Family Foundation</b>,
      an I.R.S. 501(c)(3) non-profit organization. The gift you gave us on
      October 31, 2012,
      in the amount of $3.00, will help us
      continue our multi-pronged mission
      of serving the motoring public, members of the Association of Colorado
      State Patrol Professionals and the Colorado State Patrol.  Please keep
      this receipt and consult your tax advisor regarding the deductibility of
      any or all of your donation as law allows. By contributing to the
      Foundation, donors acknowledge that our Board of Trustees has full
      authority to apply contributions as needed.
      </p> <p><i>No goods or services were received for this donation</i></p>
END;
    return $output;
  }
}
