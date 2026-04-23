<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'ca_online';
  const dbHost = 'localhost';
  const dbUser = 'ddconline';
  const dbPassword = 'jVSD#xWG8h*6';

  const webRoot = '/var/www_casafedriver';
	public static function staticHttpUrl()
  {
    return 'http://www.californiasafedriver.com/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://www.californiasafedriver.com/';
  }

  const adminEmailAddress = 'info@californiasafedriver.com';
}
