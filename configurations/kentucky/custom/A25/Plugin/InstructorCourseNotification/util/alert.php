<?php

define('_VALID_MOS',1);
require_once(dirname(__FILE__) . '/../../../autoload.php');
require_once(dirname(__FILE__) . '/../../../plugins/InstructorCourseNotification/A25/Remind/InstructorCourseNotification.php');
require_once(dirname(__FILE__) . '/../../../includes/joomla.php');
$reminder = new A25_Remind_InstructorCourseNotification();
$count = $reminder->send();
echo "Notified instructors for $count courses.\n";