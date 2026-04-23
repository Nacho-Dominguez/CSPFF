<?php

/**
 * Set flag that this is a parent file
 */
define( '_VALID_MOS', 1 );

require_once dirname(__FILE__) . '/../../globals.php';
require_once dirname(__FILE__) . '/../../configuration.php';
require_once dirname(__FILE__) . '/../../includes/joomla.php';


$course_id = (int) mosGetParam( $_REQUEST, 'id', 0 );

$course = A25_Record_Course::retrieve($course_id);

$surveyprinter = new A25_SurveyPrinter();
$surveyprinter->generate($course);

?>
