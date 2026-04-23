<?php

class HTML_pay {

	function cpanel( ) {
		global $my;
		?>
		<table class="adminheading" border="0">
		<tr><th class="cpanel">Payments</th></tr>
		</table>
		<table class="adminform">
		<tr>
		<td width="55%" valign="top">
		<div id="cpanel">
		<?php
		$link = 'index2.php?option=com_pay&amp;task=listpay';
		HTML_pay::quickiconButton( $link, 'properties.png', 'List Payments' );
		?>
		</div>
		</td>
		<td width="45%" valign="top">
		<div style="width: 100%;">
		<h3>About Payments</h3>
		<p><strong>Please Note: </strong>Payment statistics have been gathered for data since January 1, 2006 to avoid problems with incomplete data from previous years.</p>
		</div>
		</td>
		</tr>
		</table>
		<?php
	}


	function listPay( &$rows, &$pageNav, $option, &$lists, &$filter, $database) {
		mosCommonHTML::loadCalendar();
		?>
		<script type="text/javascript">
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
			$('applybutton').disabled = false;
			$('resetbutton').disabled = true;
		}
		</script>
		<form action="index2.php" method="get" name="adminForm">
		<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>" />
		<table class="adminheading">
		<tr>
			<th rowspan="2">List Payments</th>
			<td>From:</td>
			<td>To:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="from" id="from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('from');" value="..." />
			</td>
			<td nowrap="nowrap">
			<input class="text_area" type="text" name="to" id="to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('to');" value="..." />
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right" nowrap="nowrap"><input type="submit" id="applybutton" value="Apply Filter(s)" style="margin-right:20px;" /><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();"/></td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">ID</th>
			<th class="title">Name</th>
			<th class="title">DOB</th>
			<th class="title">Address</th>
			<th class="title">Phone</th>
			<th class="title">Amount</th>
			<th class="title">Check #</th>
			<th class="title">Paid Date</th>
			<th class="title">Pay Method</th>
			<th class="title">Transaction ID</th>
			<th class="title">Paid By</th>
			<th class="title">Taken By</th>
			<th class="title">Course/Notes</th>
		</tr>
		<tr>
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
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$row->id = $row->pay_id;
			$link 	= 'index2.php?option=com_pay&task=viewA&id='. $row->id;

			//get order item type
			$order = A25_Record_Order::retrieve($row->order_id);

			//set supplemental to false
			$is_supplemental_payment = false;
			/**
			 * Here, it appears that the original coders made a (risky)
			 * assumption that a supplemental payment only has 1 order item.
			 * Farther down, $order_item is used if $is_supplemental_payment is
			 * true, so they assume that the last $order_item is the only one.
			 */
			foreach ($order->OrderItems as $order_item) {
				if(($order_item->type_id != A25_Record_OrderItemType::typeId_CourseFee) && ($order_item->type_id != A25_Record_OrderItemType::typeId_CreditCardFee)) {
					//looks like this is a supplemental payment!
					$is_supplemental_payment = true;
				}
			}

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<a href="<?php echo $link; ?>" title="Payment Details">
					<?php echo $row->pay_id; ?>
					</a>
				</td>
				<td>
    				<?php echo $row->first_name . ' ' . $row->last_name; ?>
				</td>
				<td>
    				<?php echo ($row->date_of_birth) ? date('m/d/Y', strtotime($row->date_of_birth)) : ''; ?>
				</td>
				<td>
    				<?php
    				echo $row->address_1 . '<br />';
    				echo ($row->address_2) ? $row->address_2 . '<br />' : '';
    				echo $row->city . ', ' . $row->state . ' ' . $row->zip . '<br />';
    				?>
				</td>
				<td>
    				<?php echo $row->home_phone; ?>
				</td>
				<td>
        			<?php echo ($row->amount) ? '$' . $row->amount : ''; ?>
    			</td>
				<td>
    				<?php echo ($row->check_number) ? $row->check_number : ''; ?>
				</td>
				<td>
    				<?php echo ($row->pay_created) ? date('m/d/Y', strtotime($row->pay_created))  : ''; ?>
				</td>
				<td>
    				<? if($row->pay_type_id == A25_Record_Pay::typeId_ScholarshipCredit ) { ?>
        				<div style="color:red"><?php echo $row->pay_type_name; ?><br>(<?php echo $row->credit_type_name; ?>)</div>
    				<? } else { ?>
        				<?php echo $row->pay_type_name; ?>
    				<? } ?>
				</td>
				<td>
    				<?php echo $row->cc_trans_id; ?>
				</td>
				<td>
    				<?php echo ($row->paid_by_name) ? $row->paid_by_name : ''; ?>
				</td>
				<td>
    				<?php echo ($row->taken_by_name) ? $row->taken_by_name : ''; ?>
				</td>
				<td>
    				<?php if($is_supplemental_payment) { ?>
        				<?php echo 'Supplemental Payment: ' . $order_item->getTypeName(); ?>
        				<?php echo '<br />' . $row->notes; ?>
    				<?php } else { ?>
        				<?php echo 'Class: ' . $row->xref_id. ' - ' . $row->location_name . ' (' ?>
        				<?php echo ($row->course_start_date) ? date('m/d/Y', strtotime($row->course_start_date)) : '' ?>
        				<?php echo ')<br />' . $row->notes; ?>
    				<?php } ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listpay" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}

	function listCreditTypes( &$rows, &$pageNav, $option, &$filter ) {
		global $my, $database;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<script type="text/javascript">
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
			$('applybutton').disabled = false;
			$('resetbutton').disabled = true;
		}
		</script>
		<form action="index2.php" method="get" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="2">List Credit Types</th>
		</tr>
		<tr>
			<td colspan="3" align="right" nowrap="nowrap"><input type="submit" id="applybutton" value="Apply Filter(s)" style="margin-right:20px;" /><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();"/></td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">ID</th>
			<th class="title">Name</th>
			<th class="title">Total Value</th>
			<th class="title">Value Remaining</th>
			<th class="title">Is Active?</th>
			<th class="title">Created</th>
			<th class="title">Modified</th>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" class="inputbox" autocomplete="off" name="filter_name" size="10" maxlength="20" value="<?php echo $filter->name; ?>" onchange="this.form.submit();" /></td>
			<td></td>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$row->id = $row->credit_type_id;
			$link 	= 'index2.php?option=com_pay&task=credittypeform&id='. $row->id;

			//get credit type
			$credit_type = A25_Record_CreditType::retrieve($row->credit_type_id);

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
					<a href="<?php echo $link; ?>" title="Credit Type Details">
					<?php echo $row->credit_type_id; ?>
					</a>
				</td>
				<td>
				<?php echo $row->credit_type_name; ?>
				</td>
				<td>
				$<?php echo $row->total_value; ?>
				</td>
				<td>
				$<?php echo $row->total_value - $row->sum_credit_value; ?>
				</td>
				<td>
				<?php echo ($row->is_active) ? 'Yes' : 'No'; ?>
				</td>
				<td>
				<?php echo ($row->credit_type_created) ? date('m/d/Y', strtotime($row->credit_type_created))  : ''; ?>
				</td>
				<td>
				<?php echo ($row->credit_type_modified) ? date('m/d/Y', strtotime($row->credit_type_modified))  : ''; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="listcredittypes" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}

	/**
	 * Show icons, ripped from mod_quickicon.php
	 * @author Christiaan van Woudenberg
	 * @version July 12, 2006
	 *
	 * @param  string $link
	 * @param  string $image
	 * @param  string $text
	 * @return void
	 */
	function quickiconButton( $link, $image, $text ) {
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}

  


}
?>
