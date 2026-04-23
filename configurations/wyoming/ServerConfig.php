<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
        const dbName = 'wyoming';
        const dbHost = 'localhost';
        const dbUser = 'wyoming';
        const dbPassword = 'TheyDontCharg3AnyMoneyForTheirCla55es';

        const webRoot = '/var/www/wy';
	public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/wy/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/wy/';
  }

	const adminEmailAddress = 'Troy.McLees@dot.state.wy.us';
	const parentLocationId = '2';
}
