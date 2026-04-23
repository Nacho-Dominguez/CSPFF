<?php

namespace Acre\A25\Payments;

class AdminAimRedirect implements PaymentRedirectInterface
{
    public function redirect(\A25_Record_Order $order, \A25_Record_Pay $pay)
    {
        $msg = 'Successfully Applied Payment';
        \A25_DI::Redirector()->redirect(
            'index2.php?option=com_student&task=viewA&id='
            . $order->Enrollment->student_id,
            $msg
        );
    }
}
