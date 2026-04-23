<?php

class A25_AimCourtOrderedDonationForm extends A25_AimDonationForm
{
    protected function getElements()
    {
        $court = new \A25_ElementMaker_CourtOrdered();
        $donation = new \A25_ElementMaker_Donation();
        $credit = new \A25_ElementMaker_CreditCard();
        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $court->elements(),
            $donation->elements(),
            $credit->elements(),
            $submit->elements()
        );
    }
}
