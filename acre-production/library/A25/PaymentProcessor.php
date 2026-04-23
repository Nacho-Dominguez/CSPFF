<?php
define('_VALID_MOS', 1);
require_once(dirname(__FILE__) .
        '/../../administrator/components/com_pay/pay.class.php');

class A25_PaymentProcessor
{
    public static function namePaymentTypeId($paymentTypeId)
    {
        switch ($paymentTypeId) {
            case A25_Record_Pay::typeId_Cash:
                return 'A cash payment';
            case A25_Record_Pay::typeId_Check:
                return 'A check';
            case A25_Record_Pay::typeId_CreditCard:
                return 'A credit card payment';
            case A25_Record_Pay::typeId_MoneyOrder:
                return 'A money order';
            case A25_Record_Pay::typeId_ScholarshipCredit:
                return 'A scholarship';
        }
        throw new Exception("Invalid payment type id: $paymentTypeId");
    }

    public static function adminSavePay(
        A25_Record_Pay $pay,
        $mosConfig_offset,
        $task,
        $credit_type_id,
        $redirector = false
    ) {
        //record credit/scholarship, if payment was made with one
        if ($pay->pay_type_id == A25_Record_Pay::typeId_ScholarshipCredit) {
            if ($credit_type_id == null) {
                throw new A25_Exception_DataConstraint('You must select a Credit/Scholarship type.');
            }
            $pay->checkAndStore();
            $pay->associateWithScholarship($credit_type_id);
        } else {
            $pay->checkAndStore();
        }

        $pay->getStudent()->updateOrdersAndEnrollmentsAfterPayment();

        if (!$redirector) {
            $redirector = new A25_Redirector();
        }
        $msg = 'Successfully Applied Payment';
        switch ($task) {
            case 'savepay':
            default:
                $redirector->redirect('index2.php?option=com_student&task=viewA&id=' .  $pay->student_id, $msg);
                break;
        }
    }
}
