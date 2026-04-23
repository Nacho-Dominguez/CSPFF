<?php

namespace Acre\TestHelpers;

abstract class PaymentFormFiller
{
    /**
     * @var PaymentFillData
     */
    protected $data;

    public function __construct($test, PaymentFillData $data)
    {
        $this->test = $test;
        $this->data = $data;
    }

    abstract public function submit();
}
