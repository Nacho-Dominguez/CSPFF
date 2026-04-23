<?php

namespace Acre\Listeners;

interface PrePaymentPostInterface
{
    public function beforePaymentPosts($enroll);
}
