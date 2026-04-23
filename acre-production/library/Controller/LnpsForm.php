<?php

use \Acre\A25\Payments\LnpsPaymentForm;
use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_LnpsForm extends Controller_AbstractPaymentForm
{
    public function createForm($student)
    {
        return new LnpsPaymentForm($student, new SimFrontendTemplate());
    }
}
