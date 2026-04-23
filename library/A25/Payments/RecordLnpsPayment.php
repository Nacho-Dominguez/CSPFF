<?php

namespace Acre\A25\Payments;

class RecordLnpsPayment implements RecordPaymentInterface
{
    /**
     * $response should be an array with keys like what SIM posts
     * back in its Relay Response.
     */
    public function recordPayment($response)
    {
        $order = \A25_Record_Order::retrieve($response['refField1']);
        return $this->recordPaymentWithOrder($response, $order);
    }

    protected function recordPaymentWithOrder($response, $order)
    {
        $pay = new \A25_Record_Pay();
        $pay->student_id = (int)$response['refField'];
        $pay->order_id = $order->order_id;
        $pay->xref_id = $order->xref_id;
        $pay->pay_type_id = \A25_Record_Pay::typeId_CreditCard;
        $pay->amount = (float)$response['amountPaid'];
        $pay->paid_by_name = $response['billingFirstName'] . ' '
                . $response['billingLastName'];
        $pay->cc_trans_id = (int)$response['orderID'];
        $pay->created = date('Y-m-d H:i:s');

        $this->checkPayMapPostconditions($pay);

        return $pay;
    }
    /**
     * Post-conditions (Since the recording of payments is so important,
     * it's worth it to check for postconditions after attempting to assign
     * the data that we got back from authorize.net.)
     */
    private function checkPayMapPostconditions($pay)
    {
        if (!(int)$pay->student_id > 0) {
            throw new \Exception('Student ID not set correctly for payment');
        }
        if (!(float)$pay->amount > round(0.00)) {
            throw new \Exception('Amount not set correctly for payment');
        }
        if ((int)$pay->cc_trans_id < 0) {
            throw new \Exception('Transaction ID not set correctly for payment');
        }
    }
}
