<?php

class A25_OldCom_Admin_ListPayHtml {
    public static function listPay( &$rows, &$pageNav, $option, &$lists, &$filter)
	{
		mosCommonHTML::loadCalendar();

		self::javascript_functions();

		self::html_common_formHeader();
		self::html_common_formHiddenInputId();
		self::html_common_tableAdminHeading();
		self::html_common_listPaymentsHeading();
		self::html_common_calendars($filter);
		self::html_common_applyButton();
		self::html_common_tableFooter();

		self::html_common_tableAdminList();
		self::html_common_listPaymentColumnHeadings();
		self::html_common_listPaymentFilters($lists, $filter);
		self::html_common_printPayments($rows);

		self::html_common_tableFooter();
		self::html_common_listFooter($pageNav);
		self::html_common_formhiddenInputs($option);
		self::html_common_formFooter();
	}
	private static function javascript_functions()
	{
		?><script type="text/javascript">
		function resetFilters() {
			var inputs = document.adminForm.getElementsByTagName("input");
			for (var x=0;x!=inputs.length;x++){
				var name = inputs[x].name;
				if (name.indexOf('ilter_') == 1) {
					inputs[x].value = '';
				}
			}

			var selects = document.adminForm.getElementsByTagName("select");
			for (var x=0;x!=selects.length;x++){
				var name = selects[x].name;
				if (name.indexOf('ilter_') == 1) {
					selects[x].selectedIndex = 0;
				}
			}
			$('applybutton').disabled = true;
			$('resetbutton').disabled = true;
		}
		</script><?php
	}
	private static function html_common_listPaymentsHeading()
	{
		$html = A25_HtmlGenerationFunctions::headerCell('List Payments', 'rowspan="2"');
		$html .= A25_HtmlGenerationFunctions::rowCell('From:');
		$html .= A25_HtmlGenerationFunctions::rowCell('To:');
		echo A25_HtmlGenerationFunctions::row($html);
	}
	private static function html_common_calendars($filter)
	{
		$htmlFrom = '<input class="text_area" type="text" name="from" id="from" size="10" maxlength="10" value="'.date('m/d/Y',$filter->from).'" />
					<input name="reset" type="reset" class="button" onclick="return showCalendar(\'from\');" value="..." />';
		$htmlTo = '<input class="text_area" type="text" name="to" id="to" size="10" maxlength="10" value="'.date('m/d/Y',$filter->to).'" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar(\'to\');" value="..." />';
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array($htmlFrom,$htmlTo),
				array('nowrap="nowrap" style="padding-right:30px;"','nowrap="nowrap"'));
	}
	private static function html_common_applyButton()
	{
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				'<input type="submit" id="applybutton" value="Apply Filter(s)" style="margin-right:20px;" /><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();"/>',
				'colspan="3" align="right" nowrap="nowrap"');
	}
	private static function html_common_listPaymentColumnHeadings()
	{
		$html = A25_HtmlGenerationFunctions::headerCell('ID','width="20"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Name','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('DOB','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Address','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Phone','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Amount','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Check #','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Paid Date','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Pay Method','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Transaction ID','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Paid By','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Taken By','class="title"');
		$html .= A25_HtmlGenerationFunctions::headerCell('Course/Notes','class="title"');
		echo A25_HtmlGenerationFunctions::row($html);
	}
	private static function html_common_listPaymentFilters($lists,$filter)
	{
		?><tr>
			<td></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_name" size="10" maxlength="20" value="<?php echo $filter->name; ?>" onchange="this.form.submit();" /></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_dob" size="10" maxlength="20" value="<?php echo $filter->dob; ?>" onchange="this.form.submit();" /></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_address" size="10" maxlength="20" value="<?php echo $filter->address; ?>" onchange="this.form.submit();" /></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_phone" size="10" maxlength="20" value="<?php echo $filter->phone; ?>" onchange="this.form.submit();" /></td>
			<td></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_check_no" size="10" maxlength="20" value="<?php echo $filter->check_no; ?>" onchange="this.form.submit();" /></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_pay_date" size="10" maxlength="20" value="<?php echo $filter->pay_date; ?>" onchange="this.form.submit();" /></td>
			<td><?php echo $lists['filter_pay_type']; ?></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_paid_by" size="10" maxlength="30" value="<?php echo $filter->paid_by; ?>" onchange="this.form.submit();" /></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_taken_by" size="10" maxlength="30" value="<?php echo $filter->taken_by; ?>" onchange="this.form.submit();" /></td>
			<td></td>
		</tr><?php
	}
	private static function html_common_paymentDetails($link,$row)
	{
		echo A25_HtmlGenerationFunctions::rowCell(
				'<a href="'.$link.'" title="Payment Details">'.$row->pay_id.'</a>');
	}
	private static function html_common_name($row)
	{
		echo A25_HtmlGenerationFunctions::rowCell($row->first_name . ' ' . $row->last_name);
	}
	private static function html_common_dateOfBirth($row)
	{
		?><td>
			<?php echo ($row->date_of_birth) ? date('m/d/Y', strtotime($row->date_of_birth)) : ''; ?>
		</td><?php
	}
	private static function html_common_address($row)
	{
		?><td>
			<?php
			echo $row->address_1 . '<br />';
			echo ($row->address_2) ? $row->address_2 . '<br />' : '';
			echo $row->city . ', ' . $row->state . ' ' . $row->zip . '<br />';
			?>
		</td><?php
	}
	private static function html_common_phoneNumber($row)
	{
		?><td>
			<?php echo $row->home_phone; ?>
		</td><?php
	}
	private static function html_common_amount($row)
	{
		?><td>
			<?php echo ($row->amount) ? '$' . $row->amount : ''; ?>
		</td><?php
	}
	private static function html_common_checkNumber($row)
	{
		?><td>
			<?php echo ($row->check_number) ? $row->check_number : ''; ?>
		</td><?php
	}
	private static function html_common_payCreated($row)
	{
		?><td>
			<?php echo ($row->pay_created) ? date('m/d/Y', strtotime($row->pay_created))  : ''; ?>
		</td><?php
	}
	private static function html_common_payType($row)
	{
		?><td>
			<? if($row->pay_type_id == A25_Record_Pay::typeId_ScholarshipCredit ) { ?>
				<div style="color:red"><?php echo $row->pay_type_name; ?><br>(<?php echo $row->credit_type_name; ?>)</div>
			<? } else { ?>
				<?php echo $row->pay_type_name; ?>
			<? } ?>
		</td><?php
	}
	private static function html_common_creditCardTransactionId($row)
	{
		?><td>
			<?php echo $row->cc_trans_id; ?>
		</td><?php
	}
	private static function html_common_paidByNAme($row)
	{
		?><td>
			<?php echo ($row->paid_by_name) ? $row->paid_by_name : ''; ?>
		</td><?php
	}
	private static function html_common_takenByName($row)
	{
		?><td>
    				<?php echo ($row->taken_by_name) ? $row->taken_by_name : ''; ?>
				</td><?php
	}
	private static function html_common_supplementalPayment($row)
	{
		$is_supplemental_payment = false;
		$order = A25_Record_Order::retrieve($row->order_id);
		foreach ($order->OrderItems as $order_item) {
			if($order_item->type_id == A25_Record_OrderItemType::typeId_ReplaceCertFee
				|| $order_item->type_id == A25_Record_OrderItemType::typeId_ReturnCheckFee
			)
			{
				$is_supplemental_payment = true;
			}
		}
		?><td>
    				<?php if($is_supplemental_payment) { ?>
        				<?php echo 'Supplemental Payment: ' . $order_item->getTypeName(); ?>
        				<?php echo '<br />' . $row->notes; ?>
    				<?php } else { ?>
        				<?php echo 'Enrollment: ' . $row->xref_id. ' - ' . $row->location_name . ' (' ?>
        				<?php echo ($row->course_start_date) ? date('m/d/Y', strtotime($row->course_start_date)) : '' ?>
        				<?php echo ')<br />' . $row->notes; ?>
    				<?php } ?>
				</td><?php
	}
	private static function html_common_formhiddenInputs($option)
	{
		?><input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listpay" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0"><?php
	}
	private static function html_common_formFooter()
	{
		?></form><?php
	}
	private static function html_common_tableFooter()
	{
		?></table><?php
	}
	private static function html_common_listFooter($pageNav)
	{
		echo $pageNav->getListFooter();
	}
	private static function html_common_formHeader()
	{
		?><form action="index2.php" method="get" name="adminForm"><?php
	}
	private static function html_common_formHiddenInputId()
	{
		?><input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" /><?php
	}
	private static function html_common_tableAdminHeading()
	{
		?><table class="adminheading"><?php
	}
	private static function html_common_tableAdminList()
	{
		?><table class="adminlist"><?php
	}
	private static function html_common_rowHeader($k)
	{
		?><tr class="<?php echo "row$k"; ?>"><?php
	}
	private static function html_common_rowFooter()
	{
		?></tr><?php
	}
	private static function html_common_printPayments($rows)
	{
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$row->id = $row->pay_id;
			$link 	= 'index2.php?option=com_pay&task=viewA&id='. $row->id;

			self::html_common_rowHeader($k);
			self::html_common_paymentDetails($link,$row);
			self::html_common_name($row);
			self::html_common_dateOfBirth($row);
			self::html_common_address($row);
			self::html_common_phoneNumber($row);
			self::html_common_amount($row);
			self::html_common_checkNumber($row);
			self::html_common_payCreated($row);
			self::html_common_payType($row);
			self::html_common_creditCardTransactionId($row);
			self::html_common_paidByNAme($row);
			self::html_common_takenByName($row);
			self::html_common_supplementalPayment($row);
			self::html_common_rowFooter();
			$k = 1 - $k;
		}
	}
}
?>
