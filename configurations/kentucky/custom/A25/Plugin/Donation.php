<?php

class A25_Plugin_Donation implements 
    A25_ListenerI_CreateEnrollmentLineItems, A25_ListenerI_AppendReceipt,
    A25_ListenerI_MarkPaidForDonation, A25_ListenerI_TopOfPaymentForm,
    A25_ListenerI_AppendPaymentForm, A25_ListenerI_TopOfPaymentViewScript
{
  public function topOfPaymentForm()
  {
    $this->addJavascriptForFlexibleDonationBox();
    return new A25_ElementMaker_DonationBox();
  }
  
  public function topOfPaymentViewScript($form)
  {
    ?>
  <div id="donationbox" class="form_element" style="padding: 11px; margin: 12px 12px 12px 0px;
       float: left; border: 1px solid #BBBBFF; font-size: 12px;
       background-color: #efefff; color: #222244; max-width: 260px;">
    <p style="font-weight: bold">Help us save lives<br/>
      <span style="font-weight: normal; font-style: italic">Include a donation with your order</span>
    </p>
    <span class="radio">
      <?php 
      $form->element->donationRadio->setDecorators(array('ViewHelper'));
      echo $form->element->donationRadio->render($form);
      $form->element->donateCustom_amount->setDecorators(array('ViewHelper', 'Errors'));
      echo $form->element->donateCustom_amount->render($form); ?>
    </span>
  </div>
    <?php
  }
  
  protected function addJavascriptForFlexibleDonationBox()
  {
    $htmlHead = A25_DI::HtmlHead();
    $htmlHead->includeJquery();
    ?>
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
    <?php
  }
  
  public function appendPaymentForm()
  {
    return new A25_ElementMaker_DonationAmount();
  }
  
  protected function donationAmount()
  {
    if ($_POST['donation_amount'])
      $amount = floatval($_POST['donation_amount']);
    if ($amount > 0)
      return $amount;
    else
      return 0;
  }
  
  public function appendCreateEnrollmentLineItems($orderRecord)
  {
    $amount = $this->donationAmount();
    
    if ($amount > 0) {
      $orderRecord->createLineItem(
        A25_Record_OrderItemType::typeId_Donation, $amount);
    }
  }
  
  /**
   * @param A25_Record_Enroll $enroll 
   */
  public function appendReceipt($enroll)
  {
    if (!$enroll->hasFeeOfType(A25_Record_OrderItemType::typeId_Donation))
      return;
    
    $donation = $enroll->getLineItemOfType(
        A25_Record_OrderItemType::typeId_Donation);
    
    $message = new A25_EmailContent_DonateNowReceipt($donation->unit_price);
    echo '
      <div style="margin: 20px;
         border: 1px solid #BBBBFF; font-size: 10px;
         background-color: #efefff; color: #222244;">
      <div style="background-color: #222244; color: #EEEEFF;
           text-align: center; font-size: 20px; padding: 2px;">Donation Receipt</div>
      <div style="margin: 10px;">';
    $message->innerHtml();
    echo '</div></div>';
  }
  
  public function appendMarkPaidForDonation($donation)
  {
    if (!empty($_POST['x_email']))
      $address = $_POST['x_email'];
    else {
      $student = $donation->getStudent();
      if ($student)
        $address = $student->email;
      else
        return;
    }
    
    A25_DI::Factory()->DonationReceipt($donation->faceValue())->send($address);
  }
}

set_include_path(
  ServerConfig::webRoot . '/plugins/Donation' . PATH_SEPARATOR
  . get_include_path()
);
