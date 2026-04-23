<?php

class Controller_ExecutePayment extends Controller
{
    public function executeTask()
    {
        $_POST['x_card_num'] = $_POST['card_number'];
        $_POST['x_card_code'] = $_POST['cvv_number'];
        $_POST['expMonth'] = $_POST['expiration_month'];
        $_POST['expYear'] = $_POST['expiration_year'];
        $_POST['x_invoice_num'] = $_POST['order_id'];
        $_POST['x_country'] = 'US';
        $_POST['pay_type_id'] = A25_Record_Pay::typeId_CreditCard;

        $order = A25_Record_Order::retrieve($_POST['order_id']);
        $enroll = $order->Enrollment;
        $_POST['xref_id'] = $enroll->xref_id;
        $_POST['student_id'] = $enroll->student_id;
        $_POST['x_cust_id'] = $enroll->student_id;

        self::fireDuringExecutePayment($enroll);

        $poster = new \Acre\A25\Payments\PostToAuthorizeNet();
        $response = $poster->process(); // Throws Exception if unsuccessful

        $redirector = new \Acre\A25\Payments\StudentAimRedirect();
        $recorder = new \Acre\A25\Payments\RecordAuthorizeNetPayment();
        $processor = new \Acre\A25\Payments\AfterPayment($order, $response, $redirector, $recorder);
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
