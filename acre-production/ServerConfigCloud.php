<?php
require_once(dirname(__FILE__) . '/ServerConfigAbstract.php');

abstract class ServerConfigCloud extends ServerConfigAbstract
{
	const seleniumHost = '174.143.243.137';
	const seleniumPort = 4449;
	const phpIncludePath = '/usr/share/php5';
}
