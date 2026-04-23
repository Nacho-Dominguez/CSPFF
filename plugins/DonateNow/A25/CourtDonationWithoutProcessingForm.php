<?php

class A25_CourtDonationWithoutProcessingForm extends A25_DonationWithoutProcessingForm
{
    protected function donationReason()
    {
        return \A25_Record_IndependentDonation::reason_CourtOrder;
    }

    protected function getElements()
    {
        $court = new \A25_ElementMaker_CourtOrdered();
        $donation = new \A25_ElementMaker_Donation();
        $submit = new \A25_ElementMaker_Submit();
        $type = new \A25_ElementMaker_PaymentType();

        return array_merge(array_merge(
            $court->elements(),
            $donation->elements(),
            $type->elements(),
            $submit->elements()
        ));
    }
}
