<?php

namespace Acre\A25\Payments;

class PaymentFormRenderer
{
    public function render(Renderable $paymentForm)
    {
        echo '
    <div style="text-align: center;">
    <div style="margin: 24px auto; background-color: #f7f7d0; padding: 32px;
        box-shadow: 0px 0px 10px #666; font-size: 14px; max-width: 360px;
        border-radius: 5px; display: inline-block; color: #444">
      <h1 style="color: black;">' . $paymentForm->heading() . '</h1><div style="text-align: left;">' . $paymentForm->output()
        . $this->securityNote() . '</div></div>' . $paymentForm->footer();
    }

    private function securityNote()
    {
        return '</div>
            <div style="color: #888899; font-size: 12px; margin-top: 24px;">
            <img src="'. \A25_Link::to('images/Lock_icon.png')
            . '" height="16" style = "vertical-align: bottom;"/> Your credit/debit card will be securely processed
        ';

    }
}
