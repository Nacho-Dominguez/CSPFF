<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
	const dbName = 'adod';
	const dbHost = 'localhost';
	const dbUser = 'adod';
	const dbPassword = 'DrivingDynamicsWith@ttitude!';

	const webRoot = '/var/adod_www';
	public static function staticHttpUrl()
  {
    return 'http://adod.coloradosafedriver.org/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://adod.coloradosafedriver.org/';
  }

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
