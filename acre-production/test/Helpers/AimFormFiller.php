<?php

namespace Acre\TestHelpers;

class AimFormFiller extends PaymentFormFiller
{
    protected $button_text = 'Submit Payment Now';

    public function submit()
    {
        $this->fillInAimCreditCardInformation();
        $this->test->clickAndWait("//input[@value='Continue']");
        $this->test->clickAndWait("//input[@value='$this->button_text']");
    }

    protected function fillInAimCreditCardInformation()
    {
        $this->test->type("x_first_name", "First");
        $this->test->type("x_last_name", "Last");
        $this->test->type("x_address", "123 Imaginary blvd");
        $this->test->type("x_city", "Golden");
        $this->test->select("x_state", "label=Colorado");
        $this->test->type("x_zip", "80401");
        $this->test->type("card_number", $this->data->card_num);
        $this->test->type("cvv_number", "123");
        $this->test->select("expiration_month", "label=12");
        $this->test->select("expiration_year", "label=2034");
    }
}
