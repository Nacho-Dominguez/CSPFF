<?php

namespace Acre\TestHelpers;

class SimDonationFiller extends SimFormFiller
{
    public function beforeFirstSubmit()
    {
        $this->test->type('x_amount', $this->data->amount);
    }
}
