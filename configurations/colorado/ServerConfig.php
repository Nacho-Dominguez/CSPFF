<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'colorado';
  const dbHost = 'localhost';
  const dbUser = 'colorado';
  const dbPassword = 'ColoradoIsDefinitelyTheBestOfThe50States.';

  const webRoot = '/var/www/co';

  public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/co/';
  }
  public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/co/';
  }

  const parentLocationId = '2';

  // Contact information:
  const adminEmailAddress = 'info@cspff.net';

  public static function timesheetRecipientEmailAddress()
  {
    return 'instructor@cspff.net, jared@cspff.net';
  }
  public static function supplyRequestRecipientEmailAddress()
  {
    return 'jared@cspff.net, erasmo@cspff.net';
  }
  public static function latePaymentNotificationRecipientEmailAddress()
  {
    return 'student@cspff.net';
  }
}