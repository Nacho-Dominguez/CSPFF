<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
	const dbName = 'idaho';
	const dbHost = 'localhost';
	const dbUser = 'idaho';
	const dbPassword = 'IdahoWasTheLastStateThatGotMoved2NewServer';
	
	const webRoot = '/var/www/id';
	public static function staticHttpUrl()
  {
    return 'http://aliveat25.us/id/';
  }
	public static function staticHttpsUrl()
  {
    return 'https://aliveat25.us/id/';
  }
    //If this is changed, the address in /etc/mail/aliases needs to be changed too
	const adminEmailAddress = 'aliveat25@itd.idaho.gov';
}
