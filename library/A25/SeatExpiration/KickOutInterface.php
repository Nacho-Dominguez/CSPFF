<?php

namespace Acre\A25\SeatExpiration;

interface KickOutInterface
{
    public function kickOutDate(\A25_Record_Course $course);
}
