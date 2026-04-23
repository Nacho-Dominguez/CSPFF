<?php

use \Acre\A25\Payments\SimPaymentForm;
use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_SimForm extends Controller_AbstractPaymentForm
{
    public function createForm($student)
    {
        return new SimPaymentForm($student, new SimFrontendTemplate());
    }
}
