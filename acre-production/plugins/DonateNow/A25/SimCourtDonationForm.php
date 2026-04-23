<?php

class A25_SimCourtDonationForm extends A25_DonationForm
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    protected function getElements()
    {
        $court = new \A25_ElementMaker_CourtOrdered();

        $amount = new A25_Form_Element_Text_Amount('x_amount');
        $moneyval = new Zend_Validate_Regex('/^[1-9]\d{0,3}(\.\d{0,2})?$/');
        $amount->addValidator($moneyval);
        $amountElements[] = $amount;

        $reason = new Zend_Form_Element_Hidden('reason');
        $reason->setValue(A25_Record_IndependentDonation::reason_CourtOrder);
        $amountElements[] = $reason;

        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $court->elements(),
            $amountElements,
            $submit->elements()
        );
    }

    protected function renderConfirmation()
    {
        $continuation = new A25_GenerateCourtDonationSimForm($this->template);
        $continuation->renderContinuation($this, 'execute-sim-donation');
    }
}
