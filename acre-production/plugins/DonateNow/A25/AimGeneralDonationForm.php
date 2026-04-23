<?php

class A25_AimGeneralDonationForm extends A25_AimDonationForm
{
    protected function securityCheck()
    {
    }

    protected function getElements()
    {
        $donation = new \A25_ElementMaker_Donation();
        $credit = new \A25_ElementMaker_CreditCard();
        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $donation->elements(),
            $credit->elements(),
            $submit->elements()
        );
    }
}
