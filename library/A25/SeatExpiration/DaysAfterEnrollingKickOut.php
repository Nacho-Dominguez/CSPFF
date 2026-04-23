<?php

namespace Acre\A25\SeatExpiration;

class DaysAfterEnrollingKickOut implements KickOutInterface
{
    public function kickOutDate(\A25_Record_Course $course)
    {
        if (\A25_DI::PlatformConfig()->kickOutBeforeDeadline == 'never') {
            return null;
        }

        // Subtract 16 hours since we are  adding it back on later
        $holiday = new \A25_Holiday(
            \A25_DI::PlatformConfig()->kickOutBeforeDeadline . ' - 16 hours',
            array('weekend')
        );
        $date = $holiday->getNextBusinessDate();
        $date .= ' 16:00:00';
        return $date;
    }
}
