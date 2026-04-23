<?php

use \Acre\A25\Payments\PaymentRedirectInterface;

class A25_AimDonationRedirect implements A25_DonationRedirectInterface
{
    public function redirect($donation_id)
    {
        A25_DI::Redirector()->redirectBasedOnRealPath('/donation-receipt?id='
          . $donation_id);
    }
}
