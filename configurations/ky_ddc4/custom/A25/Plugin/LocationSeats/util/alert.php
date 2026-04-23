<?php

define('_VALID_MOS',1);
require_once(dirname(__FILE__) . '/../../../autoload.php');
require_once(dirname(__FILE__) . '/../../../plugins/LocationSeats/A25/Remind/Locations.php');
require_once(dirname(__FILE__) . '/../../../includes/joomla.php');
$reminder = new A25_Remind_Locations();
$count = $reminder->send();
echo "Notified instructors for $count locations.\n";