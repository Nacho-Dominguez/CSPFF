<?php

namespace Acre\A25\Payments;

interface PaymentRedirectInterface
{
    public function redirect(\A25_Record_Order $order, \A25_Record_Pay $pay);
}
