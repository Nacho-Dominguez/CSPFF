<?php

use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_FundDonation extends Controller
{
    public function executeTask()
    {
        $form = new A25_SimFundDonationForm(new SimFrontendTemplate());
        $form->run();
    }
}
