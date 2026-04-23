<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'maine';
  const dbHost = 'localhost';
  const dbUser = 'maine';
  const dbPassword = 'z%QZ$jW7xQY&';

  const webRoot = '/var/www/maine';
	public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/maine/';
  }
	public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/maine/';
  }

  const adminEmailAddress = 'aliveat25@nscnne.org';
}
