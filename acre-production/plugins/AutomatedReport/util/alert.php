<?php

define('_VALID_MOS',1);
require_once(dirname(__FILE__) . '/../../../autoload.php');
require_once(dirname(__FILE__) . '/../../../plugins/AutomatedReport/A25/Remind/AutomatedReport.php');
require_once(dirname(__FILE__) . '/../../../includes/joomla.php');
$reminder = new A25_Remind_AutomatedReport();
$count = $reminder->send();
echo "Sent daily registration report";