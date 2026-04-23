<?php

class Controller_DonationReceipt extends Controller
{
  public function executeTask()
  {
    if ($_POST['email']) {
      echo 'Email sent to ' . $_POST['email'];
      $this->sendEmail();
    }
    echo $this->receiptHtml();
  }
  
  protected function receiptHtml()
  {
    $donation = A25_Record_IndependentDonation::retrieve($_GET['id']);
    $receipt = new A25_EmailContent_DonateNowReceipt($donation->amount,
        $donation->created, $donation->reason, $donation->benefactor,
        $donation->defendant, $donation->court_id);
    ob_start();
    if (!$_GET['printable']) {
      ?>
      <div style="margin: 24px; background-color: #f7f7d0; padding: 12px 32px;
          box-shadow: 0px 0px 10px #666; font-size: 14px;
          border-radius: 5px; display: inline-block; color: #444">
      <?php
    }
    $receipt->innerHtml();
    if ($_GET['printable']) {
      exit();
    }
    ?>
    </div>
    <div style="margin: 0px 40px 24px 40px; color: #666">
      <p>Please print this receipt for your records.
      <a href="<?php echo A25_Link::withoutSef('/donation-receipt?id=' . $_GET['id'] . '&printable=1') ?>">Printable view</a></p>
      <p>If you wish, you may also email this receipt to:</p>
      <form method="POST" action="<?php echo 'donation-receipt?id=' . $_GET['id'] ?>">
        <input type="text" name="email" placeholder="email@domain.com" />
        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
        <input type="submit" value="Email Receipt" />
      </form>
    </div>
    <?php
    return ob_get_clean();
  }
  
  private function sendEmail()
  {
    $donation = A25_Record_IndependentDonation::retrieve($_POST['id']);
    $email = new A25_Envelope(new A25_EmailContent_DonateNowReceipt(
        $donation->amount, $donation->created, $donation->reason,
        $donation->benefactor, $donation->defendant, $donation->court_id));
    $email->send($_POST['email']);
  }
}
