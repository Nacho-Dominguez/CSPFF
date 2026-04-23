<?php

class Controller_Administrator_CourtDonationWithoutProcessing extends Controller
{
    public function executeTask()
    {
        $this->securityCheck();
        $form = new A25_CourtDonationWithoutProcessingForm();
        $form->run();
    }

    protected function securityCheck()
    {
        if (!A25_DI::User()->isAdminOrHigher()) {
            throw new Exception('Permission denied.');
        }
    }
}
