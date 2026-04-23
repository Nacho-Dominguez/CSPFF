<?php

use \Acre\A25\Payments\SimChecker;

class Controller_ExecuteSimFundDonation extends Controller
{
    public function executeTask()
    {
        SimChecker::throwExceptionIfSpoofedPayment();
        SimChecker::throwExceptionIfDeclinedAndGoBack(2);

        // Map to POST to match old AIM interface:
        $_POST['benefactor'] = $_POST['x_first_name'] . ' '
            . $_POST['x_last_name'];
        $transaction_id = $_POST['x_trans_id'];

        $recorder = new A25_RecordFundDonation(new A25_SimFundDonationRedirect());
        $recorder->recordAndRedirect($transaction_id);
    }
}
