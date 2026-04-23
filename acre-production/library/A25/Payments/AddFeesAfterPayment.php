<?php

namespace Acre\A25\Payments;

/**
 * The primary purpose of this class is to add donation items if they were
 * added in the payment form. In most cases, if there is a late fee, it will
 * have already been added. (Every time a student views their account, it
 * checks whether a late fee is needed.) If it hadn't been added yet, it
 * wouldn't have been paid in the submission, so if it is added here, it will
 * be unpaid.
 */
class AddFeesAfterPayment extends \A25_StrictObject
{
    private $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function addNewFees()
    {
        $this->order->addLateFeeIfNecessary();

        $this->fireDuringAddNewFees();
    }

    private function fireDuringAddNewFees()
    {
        foreach (\A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \A25_ListenerI_CreateEnrollmentLineItems) {
                $listener->appendCreateEnrollmentLineItems($this->order);
            }
        }
    }
}
