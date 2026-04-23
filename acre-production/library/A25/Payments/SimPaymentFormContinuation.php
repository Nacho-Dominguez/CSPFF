<?php

namespace Acre\A25\Payments;

abstract class SimPaymentFormContinuation extends PaymentFormContinuation
{
    protected $template;

    public function __construct($template)
    {
        $this->template = $template;
    }

    public function renderContinuation(PaymentForm $purchase, $action)
    {
        $this->output .= '<form
            action="https://secure2.authorize.net/gateway/transact.dll"
            method="POST" id="toAuthorizeNet">';

        $data = new SimFormGenerationData();
        $fields = array(
            'x_login' => \PlatformConfig::AUTHORIZE_NET_LOGIN,
            'x_fp_hash' => $data->fingerprint($purchase->amount()),
            'x_fp_sequence' => $data->sequence(),
            'x_fp_timestamp' => $data->timestamp(),
            'x_test_request' => $data->isTestRequest(),
            'x_show_form' => 'TRUE',
            'x_method' => 'CC',
            'x_show_form' => 'PAYMENT_FORM',
            //'x_header_html_payment_form' => $this->template->paymentFormHeader(),
            //'x_footer2_html_payment_form' => $this->template->paymentFormFooter(),
            'x_relay_response' => 'TRUE',
            'x_relay_url' => \ServerConfig::relayResponseUrl($action),
        );
        $fields = array_merge($fields, $this->customFields($purchase));
        $this->createHiddenInputs($fields);
        $this->createHiddenInputs($_POST);
        $this->output .= "<p>
            <input type='submit' value='Click here if you are not automatically redirected' />
            </p>\n</form>";
        $this->output .= '<script type="text/javascript">
            document.getElementById("toAuthorizeNet").submit();</script>';
        echo $this->output;
    }

    abstract protected function customFields($purchase);
}
