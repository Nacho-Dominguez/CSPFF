<?php

use \Acre\A25\Payments\SimFrontendTemplate;

class Controller_CourtOrderedDonation extends Controller
{
    public function executeTask()
    {
        if (\A25_DI::PlatformConfig()->paymentForm == 'sim-form') {
            $form = new A25_SimCourtDonationForm(new SimFrontendTemplate());
        } else {
            $form = new A25_AimCourtOrderedDonationForm();
        }
        $form->run();
    }
}
