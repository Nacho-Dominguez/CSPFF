<?php

namespace Acre\A25\Payments;

class LnpsPaymentForm extends FrontendEnrollmentPaymentForm
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
        $amount = new \Zend_Form_Element_Hidden('productAmount');
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
        if ($_POST['productAmount']) {
            return $_POST['productAmount'];
        }
        return $this->student->getAccountBalance();
    }

    protected function renderConfirmation()
    {
        $continuation = new LnpsPaymentFormContinuation($this->template);
        $continuation->renderContinuation($this);
    }
}
