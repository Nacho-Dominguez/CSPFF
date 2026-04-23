<?php

require_once ServerConfig::webRoot . '/plugins/Donation.php';

class A25_EmailContent_DonateNowReceipt extends A25_EmailContent
{
  private $_amount;
  private $_defendant;
  private $_court;
  private $_date;
  private $_benefactor;
  private $_reason;
  public function __construct($amount, $date = null, $reason = null, $benefactor = null, $defendant = null, $court_id = null)
  {
    $this->_amount = $amount;

    if ($date) {
      $this->_date = date('F j, Y', strtotime($date));
    }
    else {
      $this->_date = date('F j, Y');
    }

    $this->_reason = $reason;
    $this->_benefactor = $benefactor;
    $this->_defendant = $defendant;

    if ($court_id) {
      $record = new A25_Record_Court();
      $court = $record->retrieve($court_id);
      $this->_court = $court->getSelectionName();
    }
  }
  public function innerHtml()
  {
    ?>
      <h3>Thank you for your gift.</h3>
      <p>
        <span style="margin-right: 20px;">
          Date: <?php echo $this->_date ?>
        </span>
        Amount: $<?php echo $this->formatNumber() ?>
      </p>
      <?php if ($this->_benefactor) {
        echo"<p>
      Donor: $this->_benefactor
      </p>";
      }
      if ($this->_reason == A25_Record_IndependentDonation::reason_CourtOrder) {
        echo '<p>
      Defendant: ' . $this->_defendant .
      '</p><p> Court: ' . $this->_court .
      '</p>
'; } ?>
      <p>
      Thank you for your donation to the <b>Colorado State Patrol Family Foundation</b>,
      an I.R.S. 501(c)(3) non-profit organization. The gift you gave us on
      <?php echo $this->_date ?>,
      in the amount of $<?php echo $this->formatNumber() ?>, will help us
      continue our multi-pronged mission
      of serving the motoring public, members of the Association of Colorado
      State Patrol Professionals and the Colorado State Patrol.  Please keep
      this receipt and consult your tax advisor regarding the deductibility of
      any or all of your donation as law allows. By contributing to the
      Foundation, donors acknowledge that our Board of Trustees has full
      authority to apply contributions as needed.
      </p> <?php
      if ($this->_reason == A25_Record_IndependentDonation::reason_LicensePlate) {
        echo '<p><i>This certificate of donation satisfies the requirement necessary to obtain a Colorado Alive at 25 Group License Plate. Please print this and bring it with you to the DMV when you go to purchase your plates.';
      }
      else {
        echo '<p><i>No goods or services were received for this donation</i></p>';
      }
  }

  protected function formatNumber()
  {
    return number_format($this->_amount, 2);
  }

  public function subject()
  {
    return A25_EmailContent::wrapSubject('Donation receipt',
    PlatformConfig::agency);
  }
}
