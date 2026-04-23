<?php

namespace Acre\TestHelpers;

class DonationFillData extends PaymentFillData
{
    public $amount;

    public function __construct(
        $amount,
        $card_num = '4007000000027',
        $include_card_code = true
    ) {
        parent::__construct($card_num, $include_card_code);
        $this->amount = $amount;
    }
}
