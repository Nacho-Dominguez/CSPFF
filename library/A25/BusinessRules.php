<?php

abstract class A25_BusinessRules
{
    abstract public function hasBeenAttended($enroll);
    abstract public function courseDate($courseDate);
    abstract public function redirectIfAlreadyEnrolledMessage();
    abstract public function tuitionAccrualDate($orderItem);
}
