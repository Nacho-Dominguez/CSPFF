<?php

class A25_FundDonationWithoutProcessingForm extends A25_DonationForm
{
    protected function getElements()
    {
        $donation = new \A25_ElementMaker_FundDonation();
        $submit = new \A25_ElementMaker_Submit();
        $type = new \A25_ElementMaker_PaymentType();

        return array_merge(
            $donation->elements(),
            $type->elements(),
            $submit->elements()
        );
    }
    protected function instantiateForm()
    {
        $donation = new A25_Record_FundDonation();
        $this->form = new A25_Form_Record_FundDonation($donation);
    }

    protected function checkFormAndRenderConfirmation()
    {
        return false;   // There's no confirmation screen for this form
    }

    protected function renderForm()
    {
        ob_start();
        $this->form->run($_POST);
        $this->output = ob_get_clean();

        $this->heading = 'Edit Donation';
    }

    protected function renderConfirmation()
    {
        // Since the parent class declares this method abstract, we have to
        // implement it. However, this class doesn't use it, because it doesn't
        // use checkFormAndRenderConfirmation. There is only 1 step to these
        // forms. Perhaps it is a sign that our inheritance tree isn't quite
        // correct.
    }
}
