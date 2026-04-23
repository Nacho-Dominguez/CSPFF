<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
  const dbName = 'ky_ddc4';
  const dbHost = 'localhost';
  const dbUser = 'ky_ddc4';
  const dbPassword = 'YT1i9ct&wxW5QgO@';

  const webRoot = '/var/www_ky_ddc4';

  public static function staticHttpUrl()
  {
    return 'http://ddc4.kentuckysafedriver.com/';
  }
  public static function staticHttpsUrl()
  {
    return 'https://ddc4.kentuckysafedriver.com/';
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
        return 'delanna@kentuckysafedriver.org';
    }
}