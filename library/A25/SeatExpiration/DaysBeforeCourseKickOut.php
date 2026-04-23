<?php

namespace Acre\A25\SeatExpiration;

class DaysBeforeCourseKickOut implements KickOutInterface
{
    public function kickOutDate(\A25_Record_Course $course)
    {
        $kickOutDate = $course->course_start_date . ' - ' .
            \A25_DI::PlatformConfig()->kickOutBeforeCourseDeadline;
        // Subtract 16 hours since we are adding it back on later
        $holiday = new \A25_Holiday(
            $kickOutDate . ' - 16 hours',
            array('weekend')
        );
        $date = $holiday->getNextBusinessDate();
        $date .= ' 16:00:00';
        return $date;
    }
}
