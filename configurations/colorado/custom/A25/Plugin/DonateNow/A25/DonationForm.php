<?php

use \Acre\A25\Payments\PaymentForm;

abstract class A25_DonationForm extends PaymentForm
{
    protected function setFooter()
    {
    }

    protected function setHeading()
    {
        $this->heading = 'Donate Now';
    }
}
