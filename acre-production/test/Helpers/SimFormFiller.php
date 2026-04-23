<?php

namespace Acre\TestHelpers;

class SimFormFiller extends PaymentFormFiller
{
    public function submit()
    {
        $this->beforeFirstSubmit();
        $this->test->clickAndWait('submit');
        $this->fillInSimCreditCardInformation();
        $this->test->clickAndWait('//*[@id="btnSubmit"]');
    }

    protected function beforeFirstSubmit()
    {
        // Simple forms don't need anything before the first submission.
    }

    private function fillInSimCreditCardInformation()
    {
        $this->test->type("x_first_name", "First");
        $this->test->type("x_last_name", "Last");
        $this->test->type("x_address", "123 Imaginary blvd");
        $this->test->type("x_city", "Golden");
        $this->test->type("x_state", "Colorado");
        $this->test->type("x_zip", "80401");
        $this->test->type("x_card_num", $this->data->card_num);
        if ($this->data->include_card_code) {
            $this->test->type("x_card_code", "123");
        }
        $this->test->type("x_exp_date", "1234");
    }
}
