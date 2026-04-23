<?php

require_once dirname(__FILE__) . '/ServerConfigAbstract.php';

abstract class ServerConfig53 extends ServerConfigAbstract
{
  static public function setErrorReporting()
  {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
    ini_set('display_errors',true);
    ini_set('log_errors',true);
  }
}
