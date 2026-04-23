<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'ddconline';
  const dbHost = 'localhost';
  const dbUser = 'ddconline';
  const dbPassword = 'jVSD#xWG8h*6';

  const webRoot = '/var/www_ddconline';
	public static function staticHttpUrl()
  {
    return 'http://ddconline.coloradosafedriver.org/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://ddconline.coloradosafedriver.org/';
  }

  const adminEmailAddress = 'info@cspff.net';

  public $onlineCourseUrl = 'https://training.nsc.org/csp';
}
