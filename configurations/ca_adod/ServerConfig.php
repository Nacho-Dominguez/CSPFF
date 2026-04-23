<?php
require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
    const dbName = 'ca_adod';
    const dbHost = 'localhost';
    const dbUser = 'ca_adod';
    const dbPassword = 'CaliforniaIsOurFirstADODForOtherState';

    const webRoot = '/var/www_ca_adod';

    public static function staticHttpUrl()
    {
        return 'http://adod.californiasafedriver.com/';
    }
    public static function staticHttpsUrl()
    {
        return 'https://adod.californiasafedriver.com/';
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
