<?php
class A25_OldCom_Admin_PaymentViewHtml {
			
	public static function viewPay(A25_Record_Pay $payment, $option ) {

		A25_DataHtmlFunctions::html_css_valignTop();
		self::html_common_viewPaymentHeader();

		$student = $payment->Student;

		self::html_common_tableHeader(60,'Student Details');
		A25_DataHtmlFunctions::html_common_studentId($student);
		A25_DataHtmlFunctions::html_common_userId($student);
		A25_DataHtmlFunctions::html_common_studentName($student);
		A25_DataHtmlFunctions::html_common_studentAddress($student);
		A25_DataHtmlFunctions::html_common_studentEmail($student);
		A25_DataHtmlFunctions::html_common_studentPrimaryPhone($student);
		A25_DataHtmlFunctions::html_common_studentSecondaryPhone($student);
		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		self::html_common_tableFooter();

		self::html_common_tableHeader(40,'Order Details');
		self::html_common_paymentId($payment);
		self::html_common_paidDate($payment);
		self::html_common_totalAmount($payment);
		self::html_common_paidBy($payment);
		self::html_common_paymentMethod($payment);
		self::html_common_ccTransactionId($payment);
		self::html_common_checkNumber($payment);
		self::html_common_notes($payment);

		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		
		self::html_common_tableFooter();
		self::html_common_viewPaymentBlankTableRow();
		self::html_common_orderItemsHeader();
		self::html_common_createOrderItemRows($payment->Order->OrderItems);
		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		self::html_common_tableFooter();
		
		self::html_common_viewPaymentFooter();

		self::html_common_formHiddenInputs($option,$payment);
	}
	private static function html_common_paymentId(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Payment ID:',$paymentRecord->pay_id));
    }
	private static function html_common_paidDate(A25_Record_Pay $paymentRecord)
	{
		$date = ($paymentRecord->created) ? date('m/d/Y', strtotime($paymentRecord->created)) : '';
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Paid Date:',$date));
    }
	private static function html_common_totalAmount(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Total Amount:','$' . $paymentRecord->amount));
    }
	private static function html_common_paidBy(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Paid By:',$paymentRecord->paid_by_name));
    }
	private static function html_common_paymentMethod(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Payment Method:',$paymentRecord->getPaymentTypeName()));
    }
	private static function html_common_ccTransactionId(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('CC Transaction ID:',$paymentRecord->cc_trans_id));
    }
	private static function html_common_checkNumber(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Check Number:',$paymentRecord->check_number));
    }
	private static function html_common_notes(A25_Record_Pay $paymentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Notes:',$paymentRecord->notes));
    }
	private static function html_common_createOrderItemRows($lineitems)
	{
		foreach ($lineitems as $lineitem) {
			echo A25_HtmlGenerationFunctions::arrayToRow(
					array($lineitem->order_id,$lineitem->getTypeName(),
							$lineitem->quantity,$lineitem->unit_price));
		}
    }
	private static function html_common_formHiddenInputs($option, A25_Record_Pay $paymentRecord)
	{
		$innerHtml = '<input type="hidden" name="option" value="' . $option . '" />
			<input type="hidden" name="student_id" value="' . $paymentRecord->student_id . '" />
			<input type="hidden" name="task" value="" />';
		echo A25_HtmlGenerationFunctions::adminForm($innerHtml);
    }
	private static function html_common_viewPaymentHeader()
	{
		?><table class="adminheading">
		<tr>
			<th>View Payment</th>
		</tr>
		</table>

		<table width="100%">
		<tr><?php
    }
	private static function html_common_viewPaymentFooter()
	{
		?></tr>
		</table><?php
    }
	private static function html_common_viewPaymentBlankTableRow()
	{
		?></tr><?php
		echo A25_DataHtmlFunctions::html_common_blankTableRow(2);
		?><tr><?php
    }
	private static function html_common_orderItemsHeader()
	{
		?><td valign="top" width="100%" colspan="2">
				<table class="adminform">
				<tr>
					<th colspan="1">Order ID </th>
					<th colspan="1">Payment Type </th>
					<th colspan="1">Quantity </th>
					<th colspan="1">Amount </th>
				</tr><?php
    }
	private static function html_common_tableHeader($precentWidth,$headerName)
	{
		?><td valign="top" width="<?php echo $precentWidth; ?>%">
				<table class="adminform"><?php
		echo A25_HtmlGenerationFunctions::singleColumnHeader($headerName,'colspan="2"');
    }
	private static function html_common_tableFooter()
	{
		?></table>
			</td><?php
    }
}
?>
