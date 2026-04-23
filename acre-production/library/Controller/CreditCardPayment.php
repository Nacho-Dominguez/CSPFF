<?php

use \Acre\A25\Payments\AimPaymentForm;

class Controller_CreditCardPayment extends Controller_AbstractPaymentForm
{
    protected function createForm($student)
    {
        return new AimPaymentForm($student);
    }
}
