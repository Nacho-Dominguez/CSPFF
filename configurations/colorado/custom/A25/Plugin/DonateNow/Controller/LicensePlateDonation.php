<?php

use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_LicensePlateDonation extends Controller
{
    public function executeTask()
    {
        if (\A25_DI::PlatformConfig()->paymentForm == 'sim-form') {
            $form = new A25_SimLicensePlateDonationForm(new SimFrontendTemplate());
        } else {
            $form = new A25_AimLicensePlateDonationForm();
        }
        $form->run();
    }
}
