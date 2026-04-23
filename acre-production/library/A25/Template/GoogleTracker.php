<?php

namespace Acre\A25\Template;

class GoogleTracker implements TrackerInterface
{
    public function insertTracker($action)
    {
        return 'onclick="pageTracker._trackEvent(\'Outgoing Image\',\''
            . $action . '\');"';
    }
}
