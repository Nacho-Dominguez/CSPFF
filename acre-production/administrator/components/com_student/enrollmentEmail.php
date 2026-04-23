<?php

define( '_VALID_MOS', 1 );

require_once( dirname(__FILE__) . '/../../../includes/joomla.php' );

require_once dirname(__FILE__) . '/../../../autoload.php';

$enroll = A25_Record_Enroll::retrieve($_REQUEST['xref_id']);

echo '<h1>' . $enroll->getEnrollmentEmailSubject() . '</h1>'
	. $enroll->getEnrollmentEmailBody();
