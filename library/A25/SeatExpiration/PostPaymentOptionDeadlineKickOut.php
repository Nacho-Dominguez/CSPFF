<?php

namespace Acre\A25\SeatExpiration;

class PostPaymentOptionDeadlineKickOut implements KickOutInterface
{
    public function kickOutDate(\A25_Record_Course $course)
    {
        if ($course->isPastPaymentOptionDeadline()) {
            return \A25_Functions::formattedDateTime(\A25_DI::PlatformConfig()
                ->kickOutAfterDeadline);
        }
    }
}
