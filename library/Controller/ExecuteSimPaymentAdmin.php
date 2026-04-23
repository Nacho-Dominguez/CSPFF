<?php

use \Acre\A25\Payments\SimChecker;

class Controller_ExecuteSimPaymentAdmin extends Controller
{
    public function executeTask()
    {
        SimChecker::throwExceptionIfSpoofedPayment();
        SimChecker::throwExceptionIfDeclinedAndGoBack(2);

        $order = A25_Record_Order::retrieve((int) $_POST['x_invoice_num']);
        $_POST['pay_type_id'] = A25_Record_Pay::typeId_CreditCard;

        $enroll = $order->Enrollment;
        self::fireDuringExecutePayment($enroll);

        $redirector = new \Acre\A25\Payments\AdminSimRedirect();
        $recorder = new \Acre\A25\Payments\RecordAuthorizeNetPayment();
        $processor = new \Acre\A25\Payments\AfterPayment($order, $_POST, $redirector, $recorder);
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
