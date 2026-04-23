<?php

namespace Acre\A25\Payments;

class LnpsPaymentFormContinuation extends PaymentFormContinuation
{
    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function renderContinuation(PaymentForm $purchase, $action = null)
    {
        $this->output .= '<form
            action="https://payments.lexisnexis.com/oob/wy/co/cheyenne/alive25"
            method="POST" id="toLexisNexis">';

        $fields = array(
            'businessUnitCode' => 20905,
            'productName' => 'Alive at 25',
            'refField' => $purchase->getEnroll()->student_id,
            'refField1' => $purchase->getEnroll()->Order->order_id,
            'refField2' => $purchase->getStudent()->first_name,
            'refField3' => $purchase->getStudent()->last_name,
        );
        $this->createHiddenInputs($fields);
        $this->createHiddenInputs($_POST);
        $this->output .= "<p>
            <input type='submit' value='Click here if you are not automatically redirected' />
            </p>\n</form>";
        $this->output .= '<script type="text/javascript">
            document.getElementById("toLexisNexis").submit();</script>';
        echo $this->output;
    }
}
