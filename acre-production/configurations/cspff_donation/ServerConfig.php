<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'cspffdonation';
  const dbHost = 'localhost';
  const dbUser = 'cspffdonation';
  const dbPassword = 'U*ySCrK8Tfv18c3v';

  const webRoot = '/var/www_cspffdonation';

  public static function staticHttpUrl()
  {
    return 'http://cspffdonation.coloradosafedriver.org/';
  }
  public static function staticHttpsUrl()
  {
    return 'https://cspffdonation.coloradosafedriver.org/';
  }

  const adminEmailAddress = 'info@cspff.net';

  public static function timesheetRecipientEmailAddress()
  {
    return 'instructor@cspff.net';
  }
  public static function supplyRequestRecipientEmailAddress()
  {
    return 'instructor@cspff.net';
  }
  public static function latePaymentNotificationRecipientEmailAddress()
  {
    return 'student@cspff.net';
  }
}
