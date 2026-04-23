<?php

namespace Acre\A25\Payments;

class GeneratePaymentSimForm extends SimPaymentFormContinuation
{
    protected function customFields($purchase)
    {
        $address = $purchase->getStudent()->address_1;
        if ($purchase->getStudent()->address_2) {
            $address .= ' ' . $purchase->getStudent()->address_2;
        }

        return array(
            'x_description' => 'Payment for ' . \PlatformConfig::courseTitle,
            'x_invoice_num' => $purchase->getEnroll()->Order->order_id,
            'x_cust_id' => $purchase->getEnroll()->student_id,
            'x_first_name' => $purchase->getStudent()->first_name,
            'x_last_name' => $purchase->getStudent()->last_name,
            'x_address' => $address,
            'x_city' => $purchase->getStudent()->city,
            'x_state' => $purchase->getStudent()->state,
            'x_zip' => $purchase->getStudent()->zip,
            'x_email' => $purchase->getStudent()->email
        );
    }
}
