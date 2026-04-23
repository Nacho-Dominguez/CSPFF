<?php

namespace Acre\A25\Payments;

class SimPaymentForm extends FrontendEnrollmentPaymentForm
{
    private $template;

    public function __construct($student, $template)
    {
        parent::__construct($student);
        $this->template = $template;
    }

    protected function setHeading()
    {
    }

    protected function setFooter()
    {
        $this->footer = $this->generator->footer();
    }

    protected function getElements()
    {
        $amount = new \Zend_Form_Element_Hidden('x_amount');
        $amount->setValue($this->amount());
        $amountElements[] = $amount;

        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $this->fireTopOfPaymentForm(),
            $amountElements,
            $this->fireAppendPaymentForm(),
            $submit->elements()
        );
    }

    public function amount()
    {
        if ($_POST['x_amount']) {
            return $_POST['x_amount'];
        }
        return $this->student->getAccountBalance();
    }

    protected function renderConfirmation()
    {
        $continuation = new GeneratePaymentSimForm($this->template);
        $continuation->renderContinuation($this, 'execute-sim-payment');
    }
}
