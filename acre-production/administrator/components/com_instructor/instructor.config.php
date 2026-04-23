<?php

// ensure this file is being included by a parent file
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/**
 * E-mail recipient for supply requests.
 */
DEFINE('_SUPPLY_REQUEST_RECIPIENT','"Supply Request" <' .
		ServerConfig::supplyRequestRecipientEmailAddress() . '>');

/**
 * Subject for supply requests.
 */
DEFINE('_SUPPLY_REQUEST_SUBJECT', PlatformConfig::courseTitle
		. ": Instructor Supply Request");

/**
 * Message body for supply requests.
 */
DEFINE('_SUPPLY_REQUEST_MSG',"Quantity Requested:\n%s\n\nSupplies Requested:\n%s\n\nFrom Instructor:\n%s");

/**
 * Subject for timesheets.
 */
DEFINE('_TIMESHEET_SUBJECT', PlatformConfig::courseTitle . ": Instructor Timesheet For Marketing");

/**
 * Message body for timesheets.
 */
DEFINE('_TIMESHEET_MSG',"Instructor Timesheet For Marketing:\n%s\n\nFrom Instructor:\n%s");

?>
