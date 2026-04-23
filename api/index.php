<?php
/**
 * The 'api' folder is meant to house controllers which only return partial data,
 * so that they can be used with ajax and other requests over HTTP.
 */

//include_once( 'globals.php' );
//require_once( 'configuration.php' );
require_once(dirname(__FILE__) . '/../autoload.php');

A25_ErrorHandler::initialize();

$task = A25_Sef::task();
	
$handler = new A25_ControllerHandler($task);
$handler->loadController();