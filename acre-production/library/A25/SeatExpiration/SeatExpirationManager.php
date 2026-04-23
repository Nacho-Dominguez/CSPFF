<?php

namespace Acre\A25\SeatExpiration;

class SeatExpirationManager
{
    private $interfaces;

    public function __construct(array $interfaces)
    {
        $this->interfaces = $interfaces;
    }
    public function setKickOutDate(\A25_Record_Enroll $enroll)
    {
        if ($enroll->exists()) {
            return;
        }

        $course = $enroll->Course;
        if ($course && $course->isPast()) {
            return;
        }

        $enroll->kick_out_date = $this->kickOutDate($course);
    }
    private function kickOutDate(\A25_Record_Course $course)
    {
        foreach ($this->interfaces as $interface) {
            $date = $interface->kickOutDate($course);
            if ($date) {
                return $date;
            }
        }
    }
}
