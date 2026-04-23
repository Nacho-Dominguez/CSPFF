<?php

namespace Acre\A25\Payments;

class StudentSimRedirect extends StudentRedirect
{
    /**
     * Public for testing only.
     */
    public function redirect(\A25_Record_Order $order, \A25_Record_Pay $pay)
    {
        $course = $order->getCourse();
        if ($course->isPastLateFeeDeadline()) {
            $this->sendLatePaymentNotification($order, $pay);
            $link = \ServerConfig::currentUrl()
                . '/after-late-payment?mosmsg=Thank+You+For+Your+Payment';
        } else {
            $link = \ServerConfig::currentUrl()
                . '/account?mosmsg=Course+Enrollment+Completed+-+Thank+You+For+Your+Payment';
        }
        \A25_DI::Redirector()->redirectUsingJavascriptLocationReplace($link);
    }
}
