<?php

use \Acre\A25\UserActionException;

/**
 * This exception should be used when an ActiveRecord object has invalid data
 * for database storage.
 */
class A25_Exception_DataConstraint extends UserActionException
{
}
