<?php

namespace Acre\A25\Payments;

class RecordAuthorizeNetPayment implements RecordPaymentInterface
{
    /**
     * $authorizeNetResponse should be an array with keys like what SIM posts
     * back in its Relay Response.
     */
    public function recordPayment($authorizeNetResponse)
    {
        $order = \A25_Record_Order::retrieve($authorizeNetResponse['x_invoice_num']);
        return $this->recordPaymentWithOrder($authorizeNetResponse, $order);
    }

    protected function recordPaymentWithOrder($authorizeNetResponse, $order)
    {
        $pay = new \A25_Record_Pay();
        $pay->student_id = (int)$authorizeNetResponse['x_cust_id'];
        $pay->order_id = $order->order_id;
        $pay->xref_id = $order->xref_id;
        $pay->pay_type_id = \A25_Record_Pay::typeId_CreditCard;
        $pay->amount = (float)$authorizeNetResponse['x_amount'];
        $pay->paid_by_name = $authorizeNetResponse['x_first_name'] . ' '
                . $authorizeNetResponse['x_last_name'];
        $pay->cc_trans_id = (int)$authorizeNetResponse['x_trans_id'];
        $pay->cc_response_code = (int)$authorizeNetResponse['x_response_code'];
        $pay->created = date('Y-m-d H:i:s');
        $pay->notes = $authorizeNetResponse['notes'];

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
        if ((int)$pay->cc_response_code != 1) {
            throw new \Exception('Response code not set correctly for payment');
        }
    }
}
