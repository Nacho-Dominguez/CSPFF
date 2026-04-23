<?php

namespace Acre\A25\Payments;

class AfterPayment extends \A25_StrictObject
{
    private $order;
    private $response;
    private $redirector;
    private $recorder;

    public function __construct(\A25_Record_Order $order, $response, PaymentRedirectInterface $redirector, RecordPaymentInterface $recorder)
    {
        $this->order = $order;
        $this->response = $response;
        $this->redirector = $redirector;
        $this->recorder = $recorder;
    }

    public function run()
    {
        $pay = $this->recorder->recordPayment($this->response);
        $pay->save();

        $feeAdder = new AddFeesAfterPayment($this->order);
        $feeAdder->addNewFees();

        $this->order->Enrollment->Student->updateOrdersAndEnrollmentsAfterPayment();

        $this->redirector->redirect($this->order, $pay);
    }
}
