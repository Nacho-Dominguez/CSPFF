<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

/**
 * Since these tests mess with the include path, if troubles arise when running
 * the entire test suite, try running these tests in their own process.
 */
class test_unit_A25_Plugin_Donation_AddJavascriptForDonationBoxTest extends
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
	public function addsJavascript()
	{
    $donation = new DonationWithJavascriptBoxExposed;
    
    ob_start();
    $donation->addJavascriptForFlexibleDonationBox();
    $box = ob_get_clean();
    
    $this->assertEquals($this->expectedOutput(), $box);
	}
  
  private function expectedOutput()
  {
    $output .= <<<END
    <script type="text/javascript">
      jQuery(function() {
        originalPaymentDue = $("#paymentDue").text();
        $("#orderSummary tr:last").before('<tr id="donationRow" style="display:none"><td width="400">Donation</td><td align="right">$<span id="donationAmountListed">'
            + '0</span></td></tr>');
        
        var updateTotalsForDonations = function(amount) {
          $("#donationAmountListed").text(amount);
          if(!amount)
            amount = 0;
          if(amount > 0)
            $("#donationRow").show();
          else
            $("#donationRow").hide();
          $("#paymentDue").text(parseFloat(originalPaymentDue) + amount);
          $("#x_amount").val(parseFloat(originalPaymentDue) + amount);
          $("#x_amount_display").text((parseFloat(originalPaymentDue) + amount).toFixed(2));
          $("#donation_amount").val(amount);
        };
        
        if($("#donationRadio-1").attr("checked") == "checked")
          updateTotalsForDonations(1);
        
        if($("#donationRadio-5").attr("checked") == "checked")
          updateTotalsForDonations(5);
        
        if($("#donationRadio-custom").attr("checked") == "checked")
          updateTotalsForDonations(parseFloat($("#donateCustom_amount").val()));
        
        $("#donationRadio-0").change(function() {
          updateTotalsForDonations(0);
        });
        $("#donationRadio-1").change(function() {
          updateTotalsForDonations(1);
        });
        $("#donationRadio-5").change(function() {
          updateTotalsForDonations(5);
        });
        $("#donationRadio-custom").change(function() {
          updateTotalsForDonations(parseFloat($("#donateCustom_amount").val()));
        });
        $("#donateCustom_amount").change(function() {
          $("#donationRadio-custom").attr("checked","checked");
          updateTotalsForDonations(parseFloat($("#donateCustom_amount").val()));
        });
      });
    </script>
    
END;
    return $output;
  }
}

class DonationWithJavascriptBoxExposed extends A25_Plugin_Donation
{
  public function addJavascriptForFlexibleDonationBox() {
    return parent::addJavascriptForFlexibleDonationBox();
  }
}
