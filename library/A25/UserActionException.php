<?php

namespace Acre\A25;

class UserActionException extends \Exception
{
    private $goBack;

    public function __construct($message, $goBack = 1)
    {
        $this->goBack = $goBack;
        parent::__construct($message);
    }

    public function getActionLink()
    {
        return '<a href onclick="window.history.go(-' . $this->goBack . ')">Go back and try again</a>';
    }
}
