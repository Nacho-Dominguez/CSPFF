<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

require_once($mainframe->getPath('admin_html'));
require_once($mainframe->getPath('class'));
require_once($mosConfig_absolute_path . '/autoload.php');
require_once($mosConfig_absolute_path . '/administrator/components/com_pay/pay.class.php');

ADMIN_pay::run();

/**
 * The main class of static functions for com_pay.
 */
class ADMIN_pay
{

    /**
     * Controls the switchboard for com_pay.
     *
     * @static
     */
    function run()
    {
        global $database, $acl, $mainframe, $mosConfig_emailpass, $option, $my, $id;

        if (!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all')
                || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_pay'))) {
            A25_DI::Redirector()->redirect('index2.php', 'You are not authorized to manage payments.');
        }

        // Get list of administerable locations for the current user.
        $locs = A25_Record_Location::getLocs();

        if (empty($locs)) {
            A25_DI::Redirector()->redirect('index2.php', 'You are not authorized to manage any payments.');
        }

        $task = trim(mosGetParam($_REQUEST, 'task', null));
        $from = trim(mosGetParam($_REQUEST, 'from', null));
        $to = trim(mosGetParam($_REQUEST, 'to', null));

        if (!($from && $to)) {
            //$from = mktime(0, 0, 0, date("m"), 1, date("Y"));
            // Edit on 2007-12-27: changed from year 2000 to current year, because
            // it was taking a long time to load.  (Thomas Albright)
            //$from = mktime(0, 0, 0, 1, 1, 2000);
            if (!empty($_GET['id'])) {
                $from = mktime(0, 0, 0, 1, 1, 2004);
            } else {
                $from = mktime(0, 0, 0, 1, 1, date("Y"));
            }
            $to = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
        } else {
            $from = strtotime($from);
            $to = strtotime($to);
        }

        $pay_type_id = trim(mosGetParam($_REQUEST, 'pay_type_id', null));
        switch ($task) {
            case "payformA":
            case "payform":
                $xref_id = trim(mosGetParam($_REQUEST, 'xref_id', null));
                $enroll = A25_Record_Enroll::retrieve($xref_id);
                A25_OldCom_Admin_PaymentForms::adminPayForm($enroll);
                break;

            case "refundform":
                A25_Allow::administratorOrHigher();
                $student = A25_Record_Student::retrieve(
                    trim(mosGetParam($_REQUEST, 'student_id', null))
                );
                $pay = new A25_Record_Pay();
                $pay->student_id = $student->student_id;
                $form = new A25_Form_Record_Refund(
                    $pay,
                    'option=com_student&task=viewA&id=' . $pay->student_id
                );
                $form->run($_POST);
                break;

            case "savepay":
                if ($pay_type_id == A25_Record_Pay::typeId_CreditCard) {
                    $order_id = trim(mosGetParam($_REQUEST, 'order_id', null));
                    ADMIN_pay::process($order_id);
                } else {
                    global $database, $my, $mosConfig_offset;

                    $pay = new A25_Record_Pay();

                    if ($_POST['pay_id'] > 0) {
                        $row = A25_Record_Pay::retrieve($_POST['pay_id']);
                    }

                    if (!$pay->bind($_POST)) {
                        throw new A25_Exception_DataConstraint($pay->getError());
                    }

                    $credit_type_id = trim(mosGetParam($_REQUEST, 'credit_type_id', null));

                    A25_PaymentProcessor::adminSavePay($pay, $mosConfig_offset, $task, $credit_type_id);
                }
                break;

            case 'viewA':
                A25_OldCom_Admin_PaymentView::viewPay($id, $option);
                break;

            case "cpanel":
                HTML_pay::cpanel($from, $to);
                break;

            case "listcredittypes":
                ADMIN_pay::listCreditTypes($from, $to, $option);
                break;

            case 'applycredittype':
            case "savecredittype":
                ADMIN_pay::saveCreditType($task);
                break;

            case "liststudentpayments":
                $student_id = trim(mosGetParam($_REQUEST, 'student_id', 0));
                $report = new A25_Report_StudentPayment($student_id);
                $report->run();
                break;

            case "credittypeform":
                A25_FormLoader::run(
                    'CreditType',
                    'option=com_pay&task=listcredittypes'
                );
                break;

            case "listpay":
            default:
                ADMIN_pay::listPay($from, $to, $option, $locs);
                break;
        }
    }


    /**
     * Lists all payments in chronological order, with the option to filter by date range, name, date of birth, address, phone, and pay type.
     * @author Christiaan van Woudenberg
     * @version June 20, 2006
     *
     * @param  string $option
     * @return void
     * @static
     */
    function listPay($from, $to, $option, $locs)
    {
        global $mainframe, $mosConfig_list_limit;
        A25_OldCom_Admin_ListPay::run($from, $to, $option, $locs, $mainframe, $mosConfig_list_limit);
    }

    /**
     * Lists all credits in alphabetical order, with the option to filter by name.
     * @author Garey Hoffman
     * @version December 20, 2006
     *
     * @param  string $from
     * @param  string $to
     * @param  string $option
     * @return void
     * @static
     */
    function listCreditTypes($from, $to, $option)
    {
        global $database, $mainframe, $mosConfig_list_limit;

        $limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', $mosConfig_list_limit));
        $limitstart = intval($mainframe->getUserStateFromRequest("viewcli{$option}limitstart", 'limitstart', 0));

        $filter = new stdClass;
        $filter->from = $from;
        $filter->to = $to;
        $filter->name   = $mainframe->getUserStateFromRequest("filter_name{$option}", 'filter_name', null);

        $where = array();


        if ($filter->name) {
            $where[] = "ct.`credit_type_name` LIKE '%$filter->name%' ";
        }

        // get the total number of records
        $query = "SELECT COUNT(*)"
        . "\n FROM #__credit_type ct"
        . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : "" )
        ;
        $database->setQuery($query);
        $total = $database->loadResult();
        if ($total < $limitstart) {
            $limitstart = 0;
        }

        require_once(ServerConfig::webRoot . '/administrator/includes/pageNavigation.php');
        $pageNav = new mosPageNav($total, $limitstart, $limit);

        $sql = "SELECT ct.*, DATE_FORMAT(ct.`created`,\"%Y-%m-%d\") AS `credit_type_created`,  DATE_FORMAT(ct.`modified`,\"%Y-%m-%d\") AS `credit_type_modified`,"
        . "\n SUM( c.`credit_value` ) AS `sum_credit_value` "
        . "\n FROM #__credit_type ct"
        . "\n LEFT JOIN #__credits c ON  (c.`credit_type_id` = ct.`credit_type_id`)"
        . ( count($where) ? "\n WHERE " . implode(' AND ', $where) : "" )
        . "\n GROUP BY ct.`credit_type_id`"
        . "\n LIMIT $pageNav->limitstart, $pageNav->limit";
        $database->setQuery($sql);
        $rows = $database->loadObjectList();
        echo $database->_errorMsg;
        //echo str_replace('#_','jos',$sql);

        HTML_pay::listCreditTypes($rows, $pageNav, $option, $filter);
    }

    /**
     * Saves credit type information to the database.
     * @author Garey Hoffman
     * @version December 20, 2006
     *
     * @param  string $option
     * @return void
     * @static
     */
    function saveCreditType($task)
    {
        global $database, $my, $mainframe, $mosConfig_offset, $mosConfig_absolute_path;

        $row = new A25_Record_CreditType();

        if ($_POST['credit_type_id'] > 0) {
            $row = A25_Record_CreditType::retrieve($_POST['credit_type_id']);
        }

        if (!$row->bind($_POST)) {
            throw new A25_Exception_DataConstraint($row->getError());
        }

        $row->checkAndStore();

        $row->checkin();


        $msg = 'Successfully Saved Credit Type: '. $row->credit_type_name;
        switch ($task) {
            case 'applycredittype':
                A25_DI::Redirector()->redirect('index2.php?option=com_pay&task=credittypeform&hidemainmenu=1&credit_type_id='. $row->credit_type_id, $msg);
                break;

            case 'savecredittype':
            default:
                A25_DI::Redirector()->redirect('index2.php?option=com_pay&task=listcredittypes', $msg);
                break;
        }
    }

    /**
     * Contact the credit card company and place the order
     *
     * @static
     */
    function process($order_id)
    {
        $poster = new \Acre\A25\Payments\PostToAuthorizeNet();
        $response = $poster->process(' (by Admin)'); // Throws Exception if unsuccessful

        $order = A25_Record_Order::retrieve($order_id);

        $redirector = new \Acre\A25\Payments\AdminAimRedirect();
        $postprocessor = new \Acre\A25\Payments\AfterPayment($order, $response, $redirector);
        $postprocessor->run();
    }
}
