<?php

class Controller_Administrator_FundDonationWithoutProcessing extends Controller
{
    public function executeTask()
    {
        $this->securityCheck();
        $form = new A25_FundDonationWithoutProcessingForm();
        $form->run();
    }

    protected function securityCheck()
    {
        if (!A25_DI::User()->isAdminOrHigher()) {
            throw new Exception('Permission denied.');
        }
    }
}
