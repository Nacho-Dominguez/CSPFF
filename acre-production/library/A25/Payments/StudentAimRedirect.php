<?php

namespace Acre\A25\Payments;

class StudentAimRedirect extends StudentRedirect
{
    /**
     * Public for testing only.
     */
    public function redirect(\A25_Record_Order $order, \A25_Record_Pay $pay)
    {
        $course = $order->getCourse();
        if ($course->isPastLateFeeDeadline()) {
            $this->sendLatePaymentNotification($order, $pay);
            \A25_DI::Redirector()->redirect(
                \A25_Link::to(
                    'after-late-payment'
                ),
                'Thank You For Your Payment'
            );
        } else {
            \A25_DI::Redirector()->redirect(
                \A25_Link::to('account'),
                'Course Enrollment Completed - Thank You For Your Payment'
            );
        }
    }
}
