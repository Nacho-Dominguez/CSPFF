<?php

use \Acre\A25\Payments\PaymentForm;
use \Acre\A25\Payments\SimPaymentFormContinuation;
use \Acre\A25\Payments\SimFormGenerationData;

class A25_GenerateGeneralDonationSimForm extends SimPaymentFormContinuation
{
    protected function customFields($purchase)
    {
        return array(
            'x_description' => 'Donation'
        );
    }
}
