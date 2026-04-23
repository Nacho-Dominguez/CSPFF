<?php

use \Acre\A25\UserActionException;

class A25_ErrorHandler extends A25_StrictObject
{
    public static function initialize()
    {
        set_exception_handler('A25_ErrorHandler::exceptionHandler');

        if (!ServerConfig::isDev) {
            set_error_handler('A25_ErrorHandler::errorHandler');
            register_shutdown_function('A25_ErrorHandler::fatalErrorShutdownHandler');
        }
    }

    /**
     * Top-level exception handler.  This displays input error messages in a
     * friendly form.  Input error messages result in a DataConstraintException,
     * because the database cannot (or should not) handle the invalid input.
     *
     * @param Exception $exception
     */
    public static function exceptionHandler(Exception $ex)
    {
        if ($ex instanceof UserActionException) {
            echo '<div style="background-color:orange; font-weight: 900">'
                    .$ex->getMessage() . "\n</div>\n" . $ex->getActionLink();
            exit();
        } elseif (ServerConfig::isDev) {
            return;
        } else {
            error_log($ex);
            A25_Emailer::emailThomasAnException($ex);

            echo self::messageToDisplay();

            exit();
        }
    }

    public static function errorHandler($code, $message, $file, $line)
    {
        if ($code === E_ERROR || $code === E_PARSE || $code === E_CORE_ERROR
                || $code === E_COMPILE_ERROR || $code === E_USER_ERROR
                || $code === E_RECOVERABLE_ERROR) {
            $body_generator = new A25_ErrorEmailBody();
            $message = $body_generator->generate("CODE: $code\n" . $message . "\n\n"
            . $file . " Line " . $line);

            A25_DI::Mailer()->mail(
                'jonathan@appdevl.net',
                ServerConfig::staticHttpUrl() . ' Error Triggered',
                $message,
                false
            );

            echo self::messageToDisplay();

            exit();
        }
    }

    private static function messageToDisplay()
    {
        return '<p>Sorry, an error has occurred.  Details have been logged and sent
    to the site administrator.  We will do our best to get this error fixed
    as soon as possible.</p><p>If you were trying to enroll, save a change
    to your account, or make a payment, it is possible that your submission
    was saved.  Please check the "<a href="' . PlatformConfig::accountUrl()
        . '">Your Account</a>" page to see if it was saved.  If it was
    not, please try again in a few hours, as this error may be fixed
    by then.</p>';
    }

    public static function fatalErrorShutdownHandler()
    {
        $last_error = error_get_last();
        $code = $last_error['type'];
        if ($code === E_ERROR || $code === E_PARSE || $code === E_CORE_ERROR
                || $code === E_COMPILE_ERROR || $code === E_USER_ERROR
                || $code === E_RECOVERABLE_ERROR) {
            self::errorHandler(
                E_ERROR,
                $last_error['message'],
                $last_error['file'],
                $last_error['line']
            );
        }
    }
}
