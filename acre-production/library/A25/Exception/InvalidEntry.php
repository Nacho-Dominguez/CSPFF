<?php

use \Acre\A25\UserActionException;

/**
 * This exception should be used when an ActiveRecord object tries to do
 * a function which requires a loaded record.
 */
class A25_Exception_InvalidEntry extends UserActionException
{
}
