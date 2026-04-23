<?php

use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_8States extends Controller
{
    public function executeTask()
    {
        $form = new A25_IndividualFundDonationForm(new SimFrontendTemplate());
        $form->run();
    }
}
