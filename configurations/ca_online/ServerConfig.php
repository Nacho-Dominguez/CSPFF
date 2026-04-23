<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'ca_online';
  const dbHost = 'localhost';
  const dbUser = 'ddconline';
  const dbPassword = 'jVSD#xWG8h*6';

  const webRoot = '/var/www_caonline';
	public static function staticHttpUrl()
  {
    return 'http://ddconline.californiasafedriver.com/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://ddconline.californiasafedriver.com/';
  }

  const adminEmailAddress = 'info@californiasafedriver.com';

  public $onlineCourseUrl = 'https://training.nsc.org/csp';
}
