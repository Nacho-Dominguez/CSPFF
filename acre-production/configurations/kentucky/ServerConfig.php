<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'kentucky';
  const dbHost = 'localhost';
  const dbUser = 'kentucky';
  const dbPassword = 'LaOESt!ocS9VtLE3';

  const webRoot = '/var/www/ky';

  public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/ky/';
  }
  public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/ky/';
  }

  const parentLocationId = '2';

  // Contact information:
  const adminEmailAddress = 'lori@kentuckysafedriver.org';
  
	public static function timesheetRecipientEmailAddress()
	{
		return 'instructor@cspff.net, jared@cspff.net';
	}
	public static function latePaymentNotificationRecipientEmailAddress()
	{
		return 'students@kentuckysafedriver.org';
	}
    public static function specialNeedsEmailAddress()
    {
        return 'debbie@kentuckysafedriver.org';
    }
}