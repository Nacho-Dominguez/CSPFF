<?php

namespace Acre\TestHelpers;

class AimDonationFiller extends AimFormFiller
{
    protected function fillInAimCreditCardInformation()
    {
        $this->test->type("x_amount", $this->data->amount);
        $this->test->type("benefactor", "First Last");
        $this->test->type("card_number", $this->data->card_num);
        $this->test->type("cvv_number", "123");
        $this->test->select("expiration_month", "label=12");
        $this->test->select("expiration_year", "label=2034");
    }
}
