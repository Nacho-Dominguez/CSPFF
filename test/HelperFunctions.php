<?php
define('_VALID_MOS', 1);
require_once(dirname(__FILE__) . '/../includes/database.php');

class test_HelperFunctions
{
    /**
     * @return A25_Db
     */
    public static function dbWithEmptySchema()
    {
        $resetter = new util_Db_Resetter();
        $resetter->createEmptySchema();
        return self::createJoomlaDatabaseObject();
    }

    /**
     * @deprecated - usually, you should use dbWithEmptySchema() instead.
     */
    public static function resetDb($filename = null)
    {
        $dbResetter = new util_Db_Resetter();
        $dbResetter->createCleanSchema();
        if ($filename) {
            $dbResetter->executeSqlScript(
                dirname(__FILE__) . '/integration/A25/' . $filename
            );
        }
    }

    public static function enable_loadOverLib()
    {
        define('_VALID_MOS', 1);
        require_once(dirname(__FILE__) . '/../includes/joomla.php');
        $GLOBALS['mainframe'] = self::createMainFrame();
    }

    public static function createMainFrame()
    {
        require_once(dirname(__FILE__) . '/../includes/joomlaClasses.php');
        return new mosMainFrame(
            self::createJoomlaDatabaseObject(),
            $option,
            '..',
            true
        );
    }

    public static function createLocs()
    {
        return array('all');
    }

    public static function createFakeMy()
    {
        $my = new stdClass();
        $my->usertype = 'Super Administrator';
        $my->id = 21;
        return $my;
    }

    /**
     * @return A25_Db
     */
    public static function createJoomlaDatabaseObject()
    {
        A25_DI::setDB(new A25_Db(
            ServerConfig::dbHost,
            ServerConfig::dbUser,
            ServerConfig::dbPassword,
            ServerConfig::dbName,
            'jos_'
        ));
        return A25_DI::DB();
    }

    /**
     *
     * @param A25_Record_Enroll $enroll
     * @return A25_Record_Pay
     */
    public static function makePaymentForEnrollmentAmount(A25_Record_Enroll $enroll)
    {
        $payment = new A25_Record_Pay();
        $payment->amount = $enroll->Order->totalAmount();
        $payment->assignEnrollment($enroll);
        $payment->pay_type_id = A25_Record_Pay::typeId_Cash;
        $payment->created = date('Y-m-d H:i:s');
        $payment->save();
        $enroll->Student->updateOrdersAndEnrollmentsAfterPayment();
        return $payment;
    }

    /**
     * @deprecated - Usually, you should use dbWithEmptySchema() instead.
     *
     * @return A25_Db
     */
    public static function createCleanDbObject()
    {
        self::resetDb();
        return self::createJoomlaDatabaseObject();
    }

    public static function stripWhitespace($string)
    {
        $newString = preg_replace('/>\s+/', ">", $string);
        $newString = preg_replace('/\s+</', "<", $newString);
        $newString = preg_replace('/></', ">\n<", $newString);
        $newString = preg_replace('/></', ">\n<", $newString);
        $newString = preg_replace('/\n{2,}/', "\n", $newString);
        $newString = preg_replace('/^\s+/', '', $newString);

        // Fix Javascript whitespacing
        $newString = preg_replace('/;\s+/', ";\n", $newString);
        $newString = preg_replace('/\{\s+/', ";\n", $newString);
        $newString = preg_replace('/\}\s+/', ";\n", $newString);
        $newString = preg_replace('/<!--\s+/', ";\n", $newString);


        return preg_replace('/\s+$/', '', $newString);
    }

    public static function stripAllWhitespace($string)
    {
        $newString = self::stripWhitespace($string);

        // This strips all whitespace down to one, so that spacing between
        // characters doesn't matter, like it doesn't in HTML:
        $newString = preg_replace('/(\w|\.|\$)\s+(\w|\.|\$)/', '$1 $2', $newString);
        return $newString;
    }
}
