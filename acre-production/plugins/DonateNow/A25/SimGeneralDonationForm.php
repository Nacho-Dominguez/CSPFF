<?php

class A25_SimGeneralDonationForm extends A25_DonationForm
{
    private $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    protected function getElements()
    {
        $amount = new A25_Form_Element_Text_Amount('x_amount');
        $moneyval = new Zend_Validate_Regex('/^[1-9]\d{0,3}(\.\d{0,2})?$/');
        $amount->addValidator($moneyval);
        $amountElements[] = $amount;

        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $amountElements,
            $submit->elements()
        );
    }

    protected function renderConfirmation()
    {
        $continuation = new A25_GenerateGeneralDonationSimForm($this->template);
        $continuation->renderContinuation($this, 'execute-sim-donation');
    }
}
