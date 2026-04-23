<?php

namespace Acre\A25\Payments;

abstract class PaymentFormContinuation extends Renderable
{
    /**
     * $purchase - object which has data about the specific purchase, like the
     * amount and the enrollment ID. It is currently of type 'PaymentForm'
     * because we extracted this code from there, and it was easiest to provide
     * some public methods from it to access its purchase data. However, it
     * would probably be better if we extracted the purchase data into its own
     * type.
     */
    abstract public function renderContinuation(PaymentForm $purchase, $action);

    protected function createHiddenInputs($elements)
    {
        foreach ($elements as $key => $value) {
            if ($key != 'submit') {
                $this->output .= "<input type='hidden' name='$key' value='"
                    . htmlspecialchars($value, ENT_QUOTES) . "' />";
            }
        }
    }
}
