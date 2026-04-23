<?php
/**
 * This is the configuration class for a specific server.  It will be different
 * on a develepment server and a live server.  Every distribution must have
 * a file named 'ServerConfig.php' with a class 'ServerConfig' that implements
 * this abstract class.
 *
 * @author Thomas Albright
 */
abstract class ServerConfigAbstract
{
    const isDev = false;
    const arePaymentsLive = false;

    const adminEmailAddress = 'jonathan@appdevl.net';
    const parentLocationId = 1;

    const nationalUrl = 'https://aliveat25.us';
  // @todo: remove the httpUrl and httpsUrl constants
    abstract public static function staticHttpUrl();
    abstract public static function staticHttpsUrl();
    public $httpUrl;
    public $httpsUrl;
    public static function relayResponseUrl($controller)
    {
        return A25_Link::https($controller);
    }

    public function __construct()
    {
        $this->httpUrl = ServerConfig::staticHttpUrl();
        $this->httpsUrl = ServerConfig::staticHttpsUrl();
    }

    public static function currentUrl()
    {
        if ($_SERVER['HTTPS']) {
            return self::httpsUrlWithoutSlash();
        } else {
            return self::httpUrlWithoutSlash();
        }
    }

    public static function httpUrlWithoutSlash()
    {
        return preg_replace('#/$#', '', ServerConfig::staticHttpUrl());
    }

    public static function httpsUrlWithoutSlash()
    {
        return preg_replace('#/$#', '', ServerConfig::staticHttpsUrl());
    }

    public static function specialNeedsEmailAddress()
    {
        return ServerConfig::adminEmailAddress;
    }
    public static function supplyRequestRecipientEmailAddress()
    {
        return ServerConfig::adminEmailAddress;
    }
    public static function timesheetRecipientEmailAddress()
    {
        return ServerConfig::adminEmailAddress;
    }
    public static function latePaymentNotificationRecipientEmailAddress()
    {
        return ServerConfig::adminEmailAddress;
    }

    /**
     * This function sets the cURL proxy, if necessary.
     * If the server doesn't need a proxy, this function
     * should just be blank.
     *
     * @param $ch a cURL handle
     * @return void
     * @author Thomas Albright
     */
    public static function setCurlProxy($ch)
    {
    }
    /**
     * Live servers should overwrite this function, turning display_errors off.
     */
    public static function setErrorReporting()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
        ini_set('display_errors', true);
        ini_set('log_errors', true);
    }
}
