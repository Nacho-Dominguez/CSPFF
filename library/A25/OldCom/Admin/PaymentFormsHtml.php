<?php

use Acre\A25\Payments\SimFormGenerationData;
use Acre\A25\Payments\SimAdminTemplate;

class A25_OldCom_Admin_PaymentFormsHtml
{
    private static function cashForm(
        $order,
        $amount,
        $isHidden = true
    ) {
        self::html_common_formHeading($order);
        if ($isHidden) {
            self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_Cash);
        } else {
            echo '<input type="hidden" name="pay_type_id" value="1" />';
            self::html_common_tableStart();
        }
        self::html_common_requiredFieldDescription();
        self::html_common_paidByNameTextBox();
        self::html_common_amountTextBox($amount);
        self::html_common_notesTextArea();
        if ($isHidden) {
            self::html_common_formTailWithDiv();
        } else {
            self::html_common_formTail();
        }
    }
    public static function payForm(A25_Record_Order $order, $lists, $account_balance)
    {
        $amount = self::adjustAmountToCharge($order->totalAmount(), $order, $account_balance);

        $total_amount_cc = $order->totalAmount();

        $amount_cc = self::adjustAmountToCharge($total_amount_cc, $order, $account_balance);

        self::javascript_toggleForm();
        A25_DataHtmlFunctions::html_css_valignTop();
        self::html_common_pageHeading();

        self::cashForm($order, $amount);

        // Check form
        self::html_common_formHeading($order);
        self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_Check);
        self::html_common_requiredFieldDescription();
        self::html_common_paidByNameTextBox();
        self::html_common_checkNumberTextBox();
        self::html_common_amountTextBox($amount);
        self::html_common_notesTextArea();
        self::html_common_formTailWithDiv();

        // Credit card form
        if (A25_DI::PlatformConfig()->paymentForm == 'lnps-form') {
            self::html_common_creditCardLnps($order, $amount_cc);
        } else {
            self::html_common_creditCardSim($order, $amount_cc);
        }
        self::html_common_formTailWithDiv();

        // Money Order
        self::html_common_formHeading($order);
        self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_MoneyOrder);
        self::html_common_requiredFieldDescription();
        self::html_common_paidByNameTextBox();
        self::html_common_amountTextBox($amount);
        self::html_common_checkNumberTextBox();
        self::html_common_notesTextArea();
        self::html_common_formTailWithDiv();

        // Scholarship Credit
        self::html_common_formHeading($order);
        self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_ScholarshipCredit);
        self::html_common_requiredFieldDescription();
        self::html_common_creditTypeSelectList($lists);
        self::html_common_amountTextBox($amount);
        self::html_common_notesTextArea();
        $amount = self::adjustAmountToCharge($order->totalAmount(), $order, $account_balance);


        self::html_common_formTailWithDiv();
        self::html_common_pageTail();
    }

    private static function javascript_toggleForm()
    {
        ?>
		<script language="javascript" type="text/javascript">
		<!--

		function toggleForm( checked, elem ) {
			$('pay_type_id1').checked = (checked == 1) ? true : false;
			$('pay_type_id2').checked = (checked == 2) ? true : false;
			$('pay_type_id3').checked = (checked == 3) ? true : false;
			$('pay_type_id4').checked = (checked == 4) ? true : false;
			$('pay_type_id5').checked = (checked == 5) ? true : false;
			$('bycash').style.display = (checked == 1) ? 'block' : 'none';
			$('bycheck').style.display = (checked == 2) ? 'block' : 'none';
			$('bycc').style.display = (checked == 3) ? 'block' : 'none';
			$('bymo').style.display = (checked == 4) ? 'block' : 'none';
			$('bycs').style.display = (checked == 5) ? 'block' : 'none';

			new Effect.BlindDown(elem, {duration: 0.2});
		}
		//-->
		</script><?php
    }

    private static function html_common_formHeading(A25_Record_Order $order)
    {
        $enroll = $order->Enrollment;
        $student = $enroll->Student;
            ?><form action="index2.php" method="post">
			<input type="hidden" name="option" value="com_pay" />
			<input type="hidden" name="task" value="savepay" />
			<input type="hidden" name="student_id" value="<?php echo $student->student_id; ?>" />
			<input type="hidden" name="order_id" value="<?php echo $order->order_id; ?>" />
			<input type="hidden" name="xref_id" value="<?php echo $enroll->xref_id; ?>" /><?php
    }

    private static function html_common_creditCardSim(A25_Record_Order $order, $amount_cc)
    {
        $enroll = $order->Enrollment;
        $student = $enroll->Student;
        $address = $student->address_1;
        if ($student->address_2) {
            $address .= ' ' . $student->address_2;
        }
            ?><form action="https://secure2.authorize.net/gateway/transact.dll" method="post">
<?php
        self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_CreditCard);
        self::html_enrollment_orderIdLabel($order);
        self::html_enrollment_creditCard_amountHiddenValue($amount_cc);
        self::html_common_notesTextArea();
        $data = new SimFormGenerationData();
        $template = new SimAdminTemplate();
        $fields = array(
            'x_login' => \PlatformConfig::AUTHORIZE_NET_LOGIN,
            'x_fp_hash' => $data->fingerprint($amount_cc),
            'x_fp_sequence' => $data->sequence(),
            'x_fp_timestamp' => $data->timestamp(),
            'x_test_request' => $data->isTestRequest(),
            'x_show_form' => 'TRUE',
            'x_method' => 'CC',
            'x_show_form' => 'PAYMENT_FORM',
            //'x_header_html_payment_form' => $template->paymentFormHeader(),
            //'x_footer2_html_payment_form' => $template->paymentFormFooter(),
            'x_relay_response' => 'TRUE',
            'x_relay_url' => \ServerConfig::relayResponseUrl('execute-sim-payment-admin'),
            'x_description' => 'Payment for ' . \PlatformConfig::courseTitle,
            'x_invoice_num' => $order->order_id,
            'x_cust_id' => $enroll->student_id,
            'x_first_name' => $student->first_name,
            'x_last_name' => $student->last_name,
            'x_address' => $address,
            'x_city' => $student->city,
            'x_state' => $student->state,
            'x_zip' => $student->zip,
            'x_email' => $student->email
        );
        self::createHiddenInputs($fields);
?>
			<input type="hidden" name="x_amount" value="<?php echo $amount_cc; ?>" />
            <?php
    }
    private static function html_common_creditCardLnps(A25_Record_Order $order, $amount_cc)
    {
        $enroll = $order->Enrollment;
        $student = $enroll->Student;
        $address = $student->address_1;
        if ($student->address_2) {
            $address .= ' ' . $student->address_2;
        }
        ?><form
        action="https://payments.lexisnexis.com/oob/wy/co/cheyenne/alive25"
        method="post">
        <?php
        self::html_common_paymentTypeRadioButton(A25_Record_Pay::typeId_CreditCard);
        self::html_enrollment_orderIdLabel($order);
        self::html_enrollment_creditCard_amountHiddenValue($amount_cc);
        $template = new SimAdminTemplate();
        $fields = array(
            'businessUnitCode' => 20905,
            'productName' => 'Alive at 25',
            'refField' => $student->student_id,
            'refField1' => $order->order_id,
            'refField2' => $student->first_name,
            'refField3' => $student->last_name
        );
        self::createHiddenInputs($fields);
        ?>
        <input type="hidden" name="productAmount" value="<?php echo $amount_cc; ?>" />
        <?php
    }

    private static function createHiddenInputs($elements)
    {
        foreach ($elements as $key => $value) {
            if ($key != 'submit') {
                echo "<input type='hidden' name='$key' value='"
                    . htmlspecialchars($value, ENT_QUOTES) . "' />";
            }
        }
    }

    private static function html_common_formTail()
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('','<input type="submit" value="Process Payment" />')
        );
        echo '</table></form>';
    }

    private static function html_common_formTailWithDiv()
    {
        self::html_common_formTail();
        echo '</div>';
    }

    private static function html_common_paidByNameTextBox($required = false)
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Paid By Name:','<input type="text" name="paid_by_name" size="30" maxlength="255" class="inputbox" value="" />'. A25_DataHtmlFunctions::html_common_requiredFieldImage())
        );
    }

    private static function html_common_pageTail()
    {
        ?></td>
		</tr>
		</table><?php
    }

    private static function html_common_amountTextBox($amount = null)
    {
        $input = '$<input type="text" name="amount" size="30" maxlength="255" class="inputbox" value="';
        if ($amount != null) {
            $input .= $amount;
        }
        $input .= '" />'.A25_DataHtmlFunctions::html_common_requiredFieldImage();
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Amount:',$input)
        );
    }

    private static function html_supplemental_creditCard_amountTextBox()
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Payment Amount:',
                    '$<input type="text" name="x_amount" size="30" maxlength="255" id="x_amount" class="inputbox" value="" />'.A25_DataHtmlFunctions::html_common_requiredFieldImage()),
            array('class="formlabel"')
        );
    }

    private static function html_enrollment_creditCard_amountHiddenValue($amount_cc)
    {
        $amountString = number_format($amount_cc, 2)
            . '<input type="hidden" name="x_amount" id="x_amount" value="'
            . number_format($amount_cc, 2) . '" />';
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Payment Amount:',
                    $amountString),
            array('class="formlabel"')
        );
    }

    private static function html_common_notesTextArea()
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Notes:<div class="small">(optional)</div>',
                        '<textarea name="notes" id="notes" rows="5" style="width:350px;" /></textarea>')
        );
    }

    private static function html_common_checkNumberTextBox($required = false)
    {
        $html = '<input type="text" name="check_number" size="30" maxlength="255" class="inputbox" value="" />';
        if ($required) {
            $html .= A25_DataHtmlFunctions::html_common_requiredFieldImage();
        }
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Check Number:',$html)
        );
    }

    private static function html_common_requiredFieldDescription()
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('','<span class="required">&#149; Required Field</span>'),
            array('width="150"')
        );
    }

    private static function html_common_creditTypeSelectList($lists)
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Credit Type:',$lists['credit_type_id'] .''. A25_DataHtmlFunctions::html_common_requiredFieldImage())
        );
    }

    private static function html_common_pageHeading()
    {
        echo A25_HtmlGenerationFunctions::tableWithOnlyHeading(
            'Process Payment',
            'class="adminheading"'
        );
        ?>
		<table class="adminform">
		<tr>
			<th colspan="2">
			Details
			</th>
		</tr>
		<tr>
			<td colspan="2"><?php
    }

    private static function html_enrollment_orderIdLabel(A25_Record_Order $order)
    {
        echo A25_HtmlGenerationFunctions::arrayToRow(
            array('Order ID:',$order->order_id),
            array('class="formlabel"')
        );
    }
    private static function html_common_paymentTypeRadioButton($payTypeId)
    {
        $payType = self::namePaymentType($payTypeId);
        switch ($payTypeId) {
            case A25_Record_Pay::typeId_MoneyOrder:
                $payTypeCode = 'mo';
                break;
            case A25_Record_Pay::typeId_ScholarshipCredit:
                $payTypeCode = 'cs';
                break;
            case A25_Record_Pay::typeId_CreditCard:
                $payTypeCode = 'cc';
                break;
            default:
                $payTypeCode = strtolower($payType);
                break;
        }
        $string = '<h3><input type="radio" name="pay_type_id" id="pay_type_id'
            . $payTypeId
            .'" value="'
            . $payTypeId
            .'" style="margin-right:15px;" onClick="toggleForm('
            . $payTypeId
            .',\'by'
            . $payTypeCode
            . '\')" />'
            . $payType
            . "</h3>\n"
            . '<div id="by'
            . $payTypeCode
            . '" style="display:none; margin-left:30px;">';

        echo $string;
        self::html_common_tableStart();
    }
    private static function html_common_tableStart()
    {
        echo '<table cellspacing="0" cellpadding="0" border="0">';
    }

    private static function namePaymentType($paymentTypeId)
    {
        $names = array ('Cash', 'Check', 'Credit Card', 'Money Order', 'Administrative Credit/Scholarship');
        return $names[$paymentTypeId-1];
    }

    /**
     * Maybe this function belongs in a different class, like PaymentForms,
     * since it is not related to presentation.
     *
     * @param <type> $amount
     * @param <type> $order
     * @param <type> $account_balance
     * @return <type>
     */
    private static function adjustAmountToCharge($amount, A25_Record_Order $order, $account_balance)
    {
        $credit = $order->totalAmount() - $account_balance;
        //check account balance versus actual course fee
        if (($account_balance > 0) && ($account_balance != $order->totalAmount())) {
            $amount = $amount - $credit;
        }
        return $amount;
    }
}
?>
