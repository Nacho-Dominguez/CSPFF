<?php
require_once(dirname(__FILE__) . '/../../ServerConfigCloudLive.php');

class ServerConfig extends ServerConfigCloudLive
{
    const dbName = 'spab';
    const dbHost = 'localhost';
    const dbUser = 'spab';
    const dbPassword = 'g$Vcc23JP8rnaB3Epne3';

    const webRoot = '/var/www_spab';

    public static function staticHttpUrl()
    {
        return 'http://spabresources.com/';
    }
    public static function staticHttpsUrl()
    {
        return 'https://spabresources.com/';
    }

    const adminEmailAddress = 'info@spabresources.com';

    public static function specialNeedsEmailAddress()
    {
        return 'jcobert@cobertsafetyprofessionals.com';
    }
    public static function timesheetRecipientEmailAddress()
    {
        return 'jcobert@cobertsafetyprofessionals.com';
    }
}
