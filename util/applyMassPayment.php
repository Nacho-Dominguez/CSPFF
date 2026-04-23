<?php

/**
 * This script is intended for when an organization pays for everyone in one or
 * multiple classes with one large check.  For example, the Colorado Army
 * National Guard tends to do this.
 */

require_once dirname(__FILE__) . '/../autoload.php';

$paidBy = 'County of Garfield School District 16';
$checkNumber = '2164';
$course_ids = array(8698, 8699, 8700, 8701, 8702, 8703);
$amountToPay = 39;

$massPayment = new util_MassCheck($course_ids, $amountToPay, $paidBy, $checkNumber);
$massPayment->execute();
