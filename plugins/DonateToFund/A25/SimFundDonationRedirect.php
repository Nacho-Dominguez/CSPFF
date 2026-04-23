<?php

use \Acre\A25\Payments\PaymentRedirectInterface;

class A25_SimFundDonationRedirect implements A25_DonationRedirectInterface
{
    public function redirect($donation_id)
    {
        $link = A25_DI::Redirector()->createUrlForRealPath('/fund-donation-receipt?id='
          . $donation_id);
        A25_DI::Redirector()->redirectUsingJavascriptLocationReplace($link);
    }
}
