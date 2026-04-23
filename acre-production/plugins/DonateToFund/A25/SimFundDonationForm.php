<?php

use \Acre\A25\Payments\PaymentForm;

class A25_SimFundDonationForm extends A25_DonationForm
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

        $fund = new A25_Form_Element_Select_FromTable('fund_id', 'fund', 'fund_id', 'name', 'is_active = 1');
        $fund->setLabel('Fund')->setRequired(true);
        $amountElements[] = $fund;

        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $amountElements,
            $submit->elements()
        );
    }

    protected function renderConfirmation()
    {
        $continuation = new A25_GenerateFundDonationSimForm($this->template);
        $continuation->renderContinuation($this, 'execute-sim-fund-donation');
    }
}
