<?php

use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_GeneralDonation extends Controller
{
    public function executeTask()
    {
        if (\A25_DI::PlatformConfig()->paymentForm == 'sim-form') {
            $form = new A25_SimGeneralDonationForm(new SimFrontendTemplate());
        } else {
            $form = new A25_AimGeneralDonationForm();
        }
        $form->run();
    }
}
