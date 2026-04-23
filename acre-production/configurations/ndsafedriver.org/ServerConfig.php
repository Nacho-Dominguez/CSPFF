<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'northdakota';
  const dbHost = 'localhost';
  const dbUser = 'northdakota';
  const dbPassword = 'TerryWeaverIs1PartOfTheNorthDakotaOffice';

  const webRoot = '/var/www/nd';

  public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/nd/';
  }
  public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/nd/';
  }

  // Contact information:
  const adminEmailAddress = 'ndsc@ndsc.org';
}