<?php

class Controller_ExecuteLnpsPayment extends Controller
{
    public function executeTask()
    {
        $order = A25_Record_Order::retrieve((int) $_GET['refField1']);
        $_GET['pay_type_id'] = A25_Record_Pay::typeId_CreditCard;
        $enroll = $order->Enrollment;
        self::fireDuringExecutePayment($enroll);

        $redirector = new \Acre\A25\Payments\StudentSimRedirect();
        $recorder = new \Acre\A25\Payments\RecordLnpsPayment();
        $processor = new \Acre\A25\Payments\AfterPayment($order, $_GET, $redirector, $recorder);
        $processor->run();
    }

    private static function fireDuringExecutePayment($enroll)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof \Acre\Listeners\PrePaymentPostInterface) {
                $listener->beforePaymentPosts($enroll);
            }
        }
    }
}
