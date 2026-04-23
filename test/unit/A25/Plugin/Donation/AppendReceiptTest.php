<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_Donation_AppendReceiptTest extends
		test_Framework_UnitTestCase
{
  private $donation;
  private $order;
	
	public function setUp()
	{
		parent::setUp();

    $this->donation = new A25_Plugin_Donation();
    $this->order = new A25_Record_Order();
		
		set_include_path($this->original_include_path . PATH_SEPARATOR
				. ServerConfig::webRoot . '/plugins/Donation');
	}

	/**
	 * @test
	 */
	public function appendsNoReceiptIfNoDonation()
	{
    ob_start();
    $this->donation->appendReceipt($this->order);
    $receipt = ob_get_clean();
    
    $this->assertEquals(null, $receipt);
	}

	/**
	 * @test
	 */
	public function appendsReceiptIfDonation()
	{
    $this->order->createLineItem(A25_Record_OrderItemType::typeId_Donation, 5);
    
    ob_start();
    $this->donation->appendReceipt($this->order);
    $receipt = ob_get_clean();
    
    $this->assertEquals($this->expectedOutput(), $receipt);
	}
  
  private function expectedOutput()
  {
    $fee = $this->order->createLineItem(A25_Record_OrderItemType::typeId_Donation, 5);
    $receipt = new A25_EmailContent_DonateNowReceipt($fee->unit_price);
    $output = <<<END

      <div style="margin: 20px;
         border: 1px solid #BBBBFF; font-size: 10px;
         background-color: #efefff; color: #222244;">
      <div style="background-color: #222244; color: #EEEEFF;
           text-align: center; font-size: 20px; padding: 2px;">Donation Receipt</div>
      <div style="margin: 10px;">
END;
    ob_start();
    $receipt->innerHtml($fee);
    $output .= ob_get_clean();
    $output .= '</div></div>';
    return $output;
  }
}
