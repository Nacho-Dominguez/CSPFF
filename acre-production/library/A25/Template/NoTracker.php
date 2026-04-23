<?php

namespace Acre\A25\Template;

class NoTracker implements TrackerInterface
{
    public function insertTracker($action)
    {
        return '';
    }
}
