<?php

abstract class A25_DonationWithoutProcessingForm extends A25_DonationForm
{
    protected function instantiateForm()
    {
        $donation = new A25_Record_IndependentDonation();
        $donation->reason = $this->donationReason();
        $this->form = new A25_Form_Record_IndependentDonation($donation);
    }

    abstract protected function donationReason();

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
