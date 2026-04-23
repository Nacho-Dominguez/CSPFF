<?php

namespace Acre\A25\Payments;

use \Acre\A25\UserActionException;

class SimChecker
{
    public static function throwExceptionIfSpoofedPayment()
    {
        $authorize = new \AuthorizeNetSIM(\PlatformConfig::AUTHORIZE_NET_LOGIN);
        // Check for secret authorize.net hash, and require a valid transaction ID
        // if not in a development environment:
        //if (!$authorize->isAuthorizeNet() || (\ServerConfig::arePaymentsLive && $authorize->transaction_id == 0)) {
            //throw new \Exception("The payment request was not properly authenticated..");
        //}
    }

    public static function throwExceptionIfDeclined($return_to)
    {
        if ((int) $_POST['x_response_code'] != 1) {
            $error_message = 'There was an error charging your credit card:<br/><br/>'
                . $_POST['x_response_reason_text']
                . '<br/><br/>Please ensure your information is correct and submit your '
                . 'card for payment again.<br/><br/>'
                . 'You may also call an ' . \PlatformConfig::agency
                . ' representative at ' . \PlatformConfig::phoneNumber
                . ' for further assistance.';

            throw new SimUserActionException($error_message, $return_to);
        }
    }

    /**
     * @todo - remove duplication with throwExceptionIfDeclined
     */
    public static function throwExceptionIfDeclinedAndGoBack($goBack)
    {
        if ((int) $_POST['x_response_code'] != 1) {
            $error_message = 'There was an error charging your credit card:<br/><br/>'
                . $_POST['x_response_reason_text']
                . '<br/><br/>Please ensure your information is correct and submit your '
                . 'card for payment again.<br/><br/>'
                . 'You may also call an ' . \PlatformConfig::agency
                . ' representative at ' . \PlatformConfig::phoneNumber
                . ' for further assistance.';

            throw new UserActionException($error_message, $goBack);
        }
    }
}
