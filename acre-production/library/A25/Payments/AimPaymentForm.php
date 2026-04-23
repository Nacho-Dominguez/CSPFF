<?php

namespace Acre\A25\Payments;

class AimPaymentForm extends FrontendEnrollmentPaymentForm
{
    protected function setFooter()
    {
        $this->footer = $this->generator->footer();
    }

    protected function setHeading()
    {
        $this->heading = 'Credit Card Payment';
    }

    protected function getElements()
    {
        $cardholder = new \A25_ElementMaker_Payment(
            $this->student->getAccountBalance(),
            $this->enroll->Order->order_id
        );
        $credit = new \A25_ElementMaker_CreditCard();
        $email = new \A25_ElementMaker_Email($this->student->email);
        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $this->fireTopOfPaymentForm(),
            $cardholder->elements(),
            $credit->elements(),
            $email->elements(),
            $this->fireAppendPaymentForm(),
            $submit->elements()
        );
    }

    protected function renderConfirmation()
    {
        $confirm = new Confirm();
        $confirm->renderContinuation($this, 'execute-payment');
    }

    public static function fireTopOfPaymentViewScript($form)
    {
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_TopOfPaymentViewScript) {
                $listener->topOfPaymentViewScript($form);
            }
        }
    }

    public static function fireBottomOfPaymentViewScript($form)
    {
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_BottomOfPaymentViewScript) {
                $listener->bottomOfPaymentViewScript($form);
            }
        }
    }
}
