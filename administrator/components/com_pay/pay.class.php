<?php

require_once(dirname(__FILE__) . '/../../../autoload.php');

// Necessary for mosDBTable:
require_once(dirname(__FILE__) . '/../../../includes/database.php');

/**
 * PayCredit class. Used to store a credit if a student cancels a class.
 * @author Garey Hoffman
 * @version January 3, 2007
 *
 * @return void
 */
class mosPayCredit extends mosDBTable
{
    /** @var int */
    public $pay_credit_id = null;
    /** @var int */
    public $student_id = null;
    /** @var int */
    public $xref_id = null;
    /** @var int */
    public $order_id = null;
    /** @var int */
    public $pay_type_id = null;
    /** @var decimal(10,2) */
    public $amount = null;
    /** @var varchar(255) */
    public $paid_by_name = null;
    /** @var varchar(255) */
    public $cc_trans_id = null;
    /** @var varchar(20) */
    public $check_number = null;
    /** @var tinyint(1) */
    public $cc_response_code = null;
    /** @var text */
    public $notes = null;
    /** @var datetime */
    public $created = null;
    /** @var int User id*/
    public $created_by = null;
    /** @var datetime */
    public $modified = null;
    /** @var int User id*/
    public $modified_by = null;

    /**
     * Instantiates the pay credit class
     * @author Garey Hoffman
     * @version January 3, 2007
     *
     * @return boolean
     */
    function mosPayCredit(&$db)
    {
        $this->mosDBTable('#__pay_credit', 'pay_credit_id', $db);
    }
    function check()
    {
        return true;
    }
}
