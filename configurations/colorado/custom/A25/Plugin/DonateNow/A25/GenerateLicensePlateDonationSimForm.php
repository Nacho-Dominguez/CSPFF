<?php

use \Acre\A25\Payments\SimPaymentFormContinuation;

class A25_GenerateLicensePlateDonationSimForm extends SimPaymentFormContinuation
{
    protected function customFields($purchase)
    {
        return array(
            'x_description' => 'Donation'
        );
    }
}
