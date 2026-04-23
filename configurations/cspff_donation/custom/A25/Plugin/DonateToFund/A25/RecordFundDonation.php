<?php

use \Acre\A25\Payments\PaymentRedirectInterface;

class A25_RecordFundDonation
{
    private $redirector;

    public function __construct(A25_DonationRedirectInterface $redirector)
    {
        $this->redirector = $redirector;
    }

    public function recordAndRedirect($transaction_id)
    {
        $donation = new A25_Record_FundDonation;
        $donation->bind($_POST);
        $donation->pay_type_id = A25_Record_Pay::typeId_CreditCard;
        $donation->cc_trans_id = $transaction_id;

        $donation->save();

        $this->checkDonationMapPostconditions($donation);

        $this->redirector->redirect($donation->id);
    }

    /**
     * Post-conditions (Since the recording of payments is so important,
     * it's worth it to check for postconditions after attempting to assign
     * the data that we got back from authorize.net.)
     */
    private function checkDonationMapPostconditions($donation)
    {
        if (!(float)$donation->amount > round(0.00)) {
            throw new \Exception('Amount not set correctly for donation');
        }
        if ((int)$donation->cc_trans_id < 0) {
            throw new \Exception('Transaction ID not set correctly for donation');
        }
    }
}
