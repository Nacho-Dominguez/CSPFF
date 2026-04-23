<?php

class Controller_Administrator_GeneralDonationWithoutProcessing extends Controller
{
    public function executeTask()
    {
        $this->securityCheck();
        $form = new A25_GeneralDonationWithoutProcessingForm();
        $form->run();
    }

    protected function securityCheck()
    {
        if (!A25_DI::User()->isAdminOrHigher()) {
            throw new Exception('Permission denied.');
        }
    }
}
