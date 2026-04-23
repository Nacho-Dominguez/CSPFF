<?php

namespace Acre\A25\Payments;

use \Acre\A25\UserActionException;

/**
 * This exception should be used when an ActiveRecord object has invalid data
 * for database storage.
 */
class SimUserActionException extends UserActionException
{
    private $return_to;

    public function __construct($message, $return_to)
    {
        $this->return_to = $return_to;
        parent::__construct($message);
    }

    public function getActionLink()
    {
        return '<a href="' . \A25_Link::to($this->return_to) . '">Go back and try again</a>';
    }
}
