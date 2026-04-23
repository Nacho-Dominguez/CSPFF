<?php

class A25_GeneralDonationWithoutProcessingForm extends A25_DonationWithoutProcessingForm
{
    protected function donationReason()
    {
        return \A25_Record_IndependentDonation::reason_None;
    }

    protected function getElements()
    {
        $donation = new \A25_ElementMaker_Donation();
        $submit = new \A25_ElementMaker_Submit();
        $type = new \A25_ElementMaker_PaymentType();

        return array_merge(array_merge(
            $donation->elements(),
            $type->elements(),
            $submit->elements()
        ));
    }
}
