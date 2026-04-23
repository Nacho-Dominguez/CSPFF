<?php

namespace Acre\A25\Payments;

class AdminSimRedirect implements PaymentRedirectInterface
{
    public function redirect(\A25_Record_Order $order, \A25_Record_Pay $pay)
    {
        \A25_DI::Redirector()->redirectUsingJavascriptLocationReplace(
            \A25_Link::https(
                'administrator/index2.php?option=com_student&task=viewA&id='
                . $order->Enrollment->student_id
                . '&mosmsg=Successfully+Applied+Payment'
            )
        );
    }
}
