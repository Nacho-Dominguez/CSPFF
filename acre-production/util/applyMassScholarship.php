<?php

/**
 * This is currently UNSAFE to use!!! Until GitHub Issue #29 is fixed.
 * 
 * This script is intended for when an an entire class is paid for with a
 * Scholarship Credit.
 */

require_once dirname(__FILE__) . '/../autoload.php';

$credit_type_id = 16;	// 16 is S.A.F.E Tuition Assistance
$course_ids = array(6879, 6880, 6881, 6882);
$amountToPay = 39;

$massPayment = new util_MassScholarshipCredit($course_ids, $amountToPay,
		$credit_type_id);
$massPayment->execute();
