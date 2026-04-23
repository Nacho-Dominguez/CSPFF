<?php

require_once(ServerConfig::webRoot . '/administrator/components/com_student/student.auth.php');

class A25_CookieMonster
{
    public static function sessionCookieName()
    {
        return md5('site' . ServerConfig::httpUrlWithoutSlash());
    }
    public static function setSitewideCookie($name, $value)
    {
        setcookie($name, $value, 0, '/');
    }
    public static function hash($seed)
    {
        // This was originally the global variable $mosConfig_secret
        $salt = 'AAGscujHILICgkm4';
        return md5($salt . md5($seed));
    }
    public static function getStudentIdFromCookie()
    {
        return mosGetParam(
            $_COOKIE,
            mosHash('studentid'. A25_CookieMonster::sessionCookieName()),
            0   // Default value. Also forces the result to be typecast as an int
        );
    }
    public static function getStudentFromCookie()
    {
        $student_id = self::getStudentIdFromCookie();
        AUTH_student::checkStudent($student_id, null, null);

        return A25_Record_Student::retrieve($student_id);
    }
}
