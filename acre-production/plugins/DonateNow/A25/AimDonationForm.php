<?php

use \Acre\A25\Payments\Confirm;

abstract class A25_AimDonationForm extends A25_DonationForm
{
    protected function renderConfirmation()
    {
        $confirm = new Confirm();
        $confirm->renderContinuation($this, 'execute-donation');
    }
}
