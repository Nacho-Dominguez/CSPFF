<?php

namespace Acre\A25\Payments;

class Confirm extends PaymentFormContinuation
{
    public function renderContinuation(PaymentForm $purchase, $action)
    {
        $this->heading = 'Please Confirm';
        $this->output .= '<p>You are about to pay $' . $purchase->amount()
            . ' via credit/debit card &#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;'
            . substr($_POST['card_number'], -4) . '.</p>';
        $this->output .= '<form action="' . $action . '" method="POST">';
        $this->createHiddenInputs($_POST);
        $this->output .= "<p><input type='submit' value='Submit Payment Now' /></p>\n</form>";

        $renderer = new PaymentFormRenderer();
        $renderer->render($this);
    }
}
