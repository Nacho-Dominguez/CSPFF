<?php

namespace Acre\A25\Payments;

class RecordPaymentTest extends \test_Framework_UnitTestCase
{
    public function testSetsData()
    {
        $student_id = 123;
        $xref_id = 456;
        $order_id = 789;
        $pay_type_id = 3;
        $amount = 79.99;
        $trans_id = 23456;
        $response_code = 1;
        $data = array(
            'pay_type_id' => $pay_type_id
        );
        $authorizeNetResponse = array(
            'x_cust_id' => $student_id,
            'x_amount' => $amount,
            'x_first_name' => 'Johnny',
            'x_last_name' => 'Test',
            'x_trans_id' => $trans_id,
            'x_response_code' => $response_code
        );

        $order = new \StdClass();
        $order->order_id = $order_id;
        $order->xref_id = $xref_id;

        $record = new RecordAuthorizeNetPaymentExposed();
        $pay = $record->recordPaymentWithOrder($authorizeNetResponse, $order);
        $this->assertEquals($student_id, $pay->student_id);
        $this->assertEquals($xref_id, $pay->xref_id);
        $this->assertEquals($order_id, $pay->order_id);
        $this->assertEquals($pay_type_id, $pay->pay_type_id);
        $this->assertEquals($amount, $pay->amount);
        $this->assertEquals('Johnny Test', $pay->paid_by_name);
        $this->assertEquals($trans_id, $pay->cc_trans_id);
        $this->assertEquals($response_code, $pay->cc_response_code);
    }
}

class RecordAuthorizeNetPaymentExposed extends RecordAuthorizeNetPayment
{
    public function recordPaymentWithOrder($authorizeNetResponse, $order)
    {
        return parent::recordPaymentWithOrder($authorizeNetResponse, $order);
    }
}
