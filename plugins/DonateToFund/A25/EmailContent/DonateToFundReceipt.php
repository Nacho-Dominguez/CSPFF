<?php

class A25_EmailContent_DonateToFundReceipt extends A25_EmailContent
{
    private $amount;
    private $date;
    private $benefactor;
    private $fund;
    public function __construct($amount, $date = null, $fund = null, $benefactor = null)
    {
        $this->amount = $amount;

        if ($date) {
            $this->date = date('F j, Y', strtotime($date));
        } else {
            $this->date = date('F j, Y');
        }

        $this->fund = $fund;
        $this->benefactor = $benefactor;
        $this->defendant = $defendant;
    }
    public function innerHtml()
    {
    ?>
      <h3>Thank you for your gift.</h3>
      <p>
        <span style="margin-right: 20px;">
          Date: <?php echo $this->date ?>
        </span>
        Amount: $<?php echo $this->formatNumber() ?>
      </p>
        <?php if ($this->benefactor) {
            echo"<p>Donor: $this->benefactor</p>";
        }
?>
      <p>
      Thank you for your donation to the <b>Colorado State Patrol Family Foundation's</b>
      <i><?php echo $this->fund->name ?></i>,
      an I.R.S. 501(c)(3) non-profit organization. The gift you gave us on
      <?php echo $this->date ?>,
      in the amount of $<?php echo $this->formatNumber() ?>, will help us
      continue our multi-pronged mission
      of serving the motoring public, members of the Association of Colorado
      State Patrol Professionals and the Colorado State Patrol.  Please keep
      this receipt and consult your tax advisor regarding the deductibility of
      any or all of your donation as law allows. By contributing to the
      Foundation, donors acknowledge that our Board of Trustees has full
      authority to apply contributions as needed.
      </p> <?php
        echo '<p><i>No goods or services were received for this donation</i></p>';
    }

    protected function formatNumber()
    {
        return number_format($this->amount, 2);
    }

    public function subject()
    {
        return A25_EmailContent::wrapSubject(
            'Donation receipt',
            PlatformConfig::agency
        );
    }
}
