<?php

require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
    const dbName = 'california';
    const dbHost = 'localhost';
    const dbUser = 'california';
    const dbPassword = 'IWasOriginallyBornInMountainViewCalifornia83';

    const webRoot = '/var/www/ca';
    public static function staticHttpsUrl()
    {
        return 'https://aliveat25.us/ca/';
    }
    public static function staticHttpUrl()
    {
        return 'http://aliveat25.us/ca/';
    }

    const adminEmailAddress = 'info@californiasafedriver.com';

    public static function specialNeedsEmailAddress()
    {
        return 'jcobert@cobertsafetyprofessionals.com';
    }
    public static function timesheetRecipientEmailAddress()
    {
        return 'jcobert@cobertsafetyprofessionals.com';
    }
}
