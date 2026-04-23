<?php
class A25_Functions
{
    const PHP_DATE_FORMAT = 'l F j, Y \a\t g:i a';

    /**
     * Returns true if the controller is running in /administrator, rather than
     * on the frontend. For example, when the request is to:
     *
     * https://aliveat25.us/administrator/general-donation
     * - returns true.
     *
     * https://aliveat25.us/general-donation
     * - returns false
     */
    public static function controllerIsRunningInAdministrator()
    {
        return (substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT']), 13) == 'administrator');
    }

    /**
     *
     * @param timestamp or string $datetime
     */
    public static function formattedDateTime($datetime = null)
    {
        if (!$datetime) {
            $datetime = time();
        } elseif (is_string($datetime)) {
            $datetime = strtotime($datetime);
        }

        return date('Y-m-d G:i:s', $datetime);
    }

    public static function stringToDate($string)
    {
        return date('Y-m-d', strtotime($string));
    }

    public static function durationToSeconds($formatted_duration)
    {
        $duration = strtotime($formatted_duration);

        $minute = date("i", $duration);
        $second = date("s", $duration);
        $hour = date("H", $duration);

        return ($second + (60 * $minute) + (3600 * $hour));
    }

    public static function addADay($time)
    {
        $timestamp = self::convertToTimestampIfNecessary($time);
        return date('Y-m-d', strtotime('+1 Day', $timestamp));
    }

  /**
   * Checks to see if an input time is in the form of a timestamp.
   * If not, it is converted.
   */
    public static function convertToTimestampIfNecessary($time)
    {
        if (!(is_numeric($time) && (int)$time == $time)) {
            return strtotime($time);
        }
        return $time;
    }

    public static function faviconPath()
    {
        $path = 'PlatformTemplates/favicon.ico';
        if (!file_exists(dirname(__FILE__) . '/../../PlatformTemplates/favicon.ico')) {
            $path = 'PlatformTemplateDefaults/favicon.ico';
        }
        return A25_Link::to($path);
    }

    public static function checkApprovedEmailDomain($address)
    {
        $domains = array('/@aliveat25.us$/', '/@coloradosafedriver.com$/', '/@coloradosafedriver.org$/',
            '/@cspff.net$/', '/@cobertsafetyprofessionals.com$/', '/@californiasafedriver.com$/', '/@kentuckysafedriver.org$/');
        foreach ($domains as $domain) {
            if (preg_match($domain, $address)) {
                return;
            }
        }
        error_log('Email was sent from ' . $address . ' which is not an approved domain.');
    }
    public static function checkCourse($course_id)
    {
        $course = A25_Record_Course::retrieve($course_id);
        if ($course->isPastEnrollmentDeadline()) {
            throw new A25_Exception_IllegalAction(
                'Sorry, the registration deadline has passed for this course.'
                . ' Please go back and choose a different course.'
            );
        }
        if ($course->openSeats() < 1) {
            throw new A25_Exception_IllegalAction(
                'Sorry, this course is full.'
                . ' Please go back and choose a different course.'
            );
        }
    }
    /**
    * Copy the named array content into the object as properties
    * only existing properties of object are filled. when undefined in hash, properties wont be deleted
    * @param array the input array
    * @param obj byref the object to fill of any class
    * @param string
    * @param boolean
    */
    public static function bindArrayToObject($array, &$obj, $ignore = '', $prefix = null, $checkSlashes = true)
    {
        if (!is_array($array) || !is_object($obj)) {
            return (false);
        }

        foreach (get_object_vars($obj) as $k => $v) {
            if (substr($k, 0, 1) != '_') {           // internal attributes of an object are ignored
                if (strpos($ignore, $k) === false) {
                    if ($prefix) {
                        $ak = $prefix . $k;
                    } else {
                        $ak = $k;
                    }
                    if (isset($array[$ak])) {
                        $obj->$k = ($checkSlashes && get_magic_quotes_gpc()) ?
                            A25_Functions::stripSlashesFromStringOrArray($array[$ak])
                            : $array[$ak];
                    }
                }
            }
        }

        return true;
    }

    /**
     * Strip slashes from strings or arrays of strings
     * @param mixed The input string or array
     * @return mixed String or array stripped of slashes
     */
    public static function stripSlashesFromStringOrArray(&$value)
    {
        $ret = '';
        if (is_string($value)) {
            $ret = stripslashes($value);
        } else {
            if (is_array($value)) {
                $ret = array();
                foreach ($value as $key => $val) {
                    $ret[$key] = A25_Functions::stripSlashesFromStringOrArray($val);
                }
            } else {
                $ret = $value;
            }
        }
        return $ret;
    }

    public static function includeCss(
        $forceHttps = false,
        $Itemid = 0,
        $option = '',
        $task = ''
    ) {
        if ($forceHttps) {
            $methodName = 'https';
        } else {
            $methodName = 'to';
        }

        $return = '<link href="'
            . A25_Link::$methodName('/templates/aliveat25/css/bootstrap.css')
            . '" rel="stylesheet" media="screen" /><link href="'
            . A25_Link::$methodName('/templates/aliveat25/css/template_css.css')
            . '" rel="stylesheet" type="text/css" /><link href="'
            . A25_Link::$methodName('/templates/' .$GLOBALS['cur_template']
            . '/custom.css') . '" rel="stylesheet" type="text/css" />'
            . '<style type="text/css">';
        if ($Itemid == 1  || $option == 'com_course'
            || $option == 'com_student' || $option == 'com_location') {
            $return .= '.content { padding: 0px !important; background:none;
                margin:0px !important; }';
        }
        if ($option == 'com_content' && $task != 'blogsection') {
            $return .= '.contentheading { display:none; }';
        }
        $backgroundColor =
            A25_DI::PlatformConfig()->colorScheme()->backgroundColor();
        $return .= '.colHeader { background-color:' . $backgroundColor
            . '} #mainlevel-nav { background-color:' . $backgroundColor
            . '} .striped thead th {background-color:' . $backgroundColor
            . '}</style>';
        return $return;
    }
}
