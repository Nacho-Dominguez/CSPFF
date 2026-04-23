<?php

namespace Acre\TestHelpers;

class PaymentFillData
{
    /**
     * Authorize.net provides 2 fake credit card numbers:
     * - 4007000000027 - approve the transaction
     * - 4222222222222 - Return the $ amount as the 'reason response code'
     */
    public $card_num;
    public $include_card_code;

    public function __construct(
        $card_num = '4007000000027',
        $include_card_code = true
    ) {
        $this->card_num = $card_num;
        $this->include_card_code = $include_card_code;
    }
}
