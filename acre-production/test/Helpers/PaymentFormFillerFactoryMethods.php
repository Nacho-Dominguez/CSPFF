<?php

namespace Acre\TestHelpers;

abstract class PaymentFormFillerFactoryMethods
{
    // Authorize.net reason response codes:
    const REASON_CODE_AVS_MISMATCH = 27;

    public static function createFrontendTuitionFormFiller($test, PaymentFillData $data)
    {
        if (\A25_DI::PlatformConfig()->paymentForm == 'sim-form') {
            return new SimFormFiller($test, $data);
        } else {
            return new AimFormFiller($test, $data);
        }
    }
    public static function createDonationFormFiller($test, PaymentFillData $data)
    {
        if (\A25_DI::PlatformConfig()->paymentForm == 'sim-form') {
            return new SimDonationFiller($test, $data);
        } else {
            return new AimDonationFiller($test, $data);
        }
    }
}
