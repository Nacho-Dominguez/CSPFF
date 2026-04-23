<?php

class A25_PlateDonationWithoutProcessingForm extends A25_DonationWithoutProcessingForm
{
    protected function donationReason()
    {
        return A25_Record_IndependentDonation::reason_LicensePlate;
    }

    protected function getElements()
    {
        $plate = new A25_ElementMaker_LicensePlate();
        $submit = new A25_ElementMaker_Submit();
        $type = new A25_ElementMaker_PaymentType();

        return array_merge(array_merge(
            $plate->elements(),
            $type->elements(),
            $submit->elements()
        ));
    }
}
