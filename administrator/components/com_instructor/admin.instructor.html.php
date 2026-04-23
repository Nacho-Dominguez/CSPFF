<?php

class HTML_instructor {

	function supplyForm( &$row, $option, &$lists ) {
		mosCommonHTML::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			if (pressbutton == 'cancelsupply') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ($F('id') == '') {
				alert( "Please choose an instructor." );
			} else if ($F('address_1') == '') {
				alert( "Please enter a delivery address." );
			} else if ($F('city') == '') {
				alert( "Please enter a delivery city." );
			} else if ($F('state') == '') {
				alert( "Please choose a delivery state." );
			} else if ($F('zip') == '') {
				alert( "Please enter a delivery zip code." );
			} else if ($F('qty_requested') == '') {
				alert( "Please enter the quantity requested." );
			} else if ($F('supplies') == '') {
				alert( "Please enter the supplies you need." );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th>Instructor Supplies Request</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<th colspan="2">
			Request Details
			</th>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<span class="required">&#149; Required Field</span>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Instructor: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['id']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			E-mail Address:
			</td>
			<td>
			<?php echo $row->email; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Request Date:
			</td>
			<td>
			<?php echo date(A25_Functions::PHP_DATE_FORMAT); ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Delivery Address: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="address_1" id="address_1" size="30" maxlength="80" class="inputbox" value="" /><br />
			<input type="text" name="address_2" id="address_2" size="30" maxlength="80" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			City: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="city" id="city" size="30" maxlength="80" class="inputbox" value="<?php echo $row->city; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			State: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['state']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Zip: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="zip" id="zip" size="12" maxlength="10" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			Phone:
			</td>
			<td>
			<input type="text" name="phone" id="phone" size="12" maxlength="12" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			Quantity Requested: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="qty_requested" id="qty_requested" size="12" maxlength="12" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td class="formlabeltop">
			Supplies Needed: <span class="required">&#149;</span>
			</td>
			<td>
			<textarea name="supplies" id="supplies" rows="15" style="width:400px;"></textarea>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="supplyform" />
		</form>
		<?php
	}


	function timeForm( &$row, $option, &$lists ) {
		mosCommonHTML::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			if (pressbutton == 'canceltime') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ($F('id') == '') {
				alert( "Please choose an instructor." );
			} else if ($F('date') == '') {
				alert( "Please enter a date." );
			} else if ($F('time') == '') {
				alert( "Please enter a time." );
			} else if ($F('description') == '') {
				alert( "Please enter a location and an activity description." );
			} else if ($F('timespent') == '') {
				alert( "Please enter an amount of time spent." );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th>Instructor Timesheet For Marketing / Advertising / ITAG</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<th colspan="2">
			Request Details
			</th>
		</tr>
		<tr>
			<td colspan="2">
				<p>Please provide both the address and description of the effort with which you were involved. For ITAG, please list the names of the instructor(s) you reviewed.</p>
			</td>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<span class="required">&#149; Required Field</span>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Instructor: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['id']; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Date: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="date" id="date" size="30" maxlength="80" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			Time: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="time" id="time" size="30" maxlength="80" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			Description: <span class="required">&#149;</span>
			</td>
			<td>
			<textarea name="description" id="description" rows="15" style="width:400px;"></textarea>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Time Spent: <span class="required">&#149;</span>
			</td>
			<td>
			<input type="text" name="timespent" id="timespent" size="30" maxlength="80" class="inputbox" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
			E-mail Address:
			</td>
			<td>
			<?php echo $row->email; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Address:
			</td>
			<td>
			<?php
			echo $row->address_1 . '<br />';
			echo ($row->address_2) ? $row->address_2 . '<br />' : '';
			echo $row->city . ', ' . $row->state . '<br />';
			echo $row->zip;
			?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Work Phone:
			</td>
			<td>
			<?php echo $row->work_phone; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Request Date:
			</td>
			<td>
			<?php echo date(A25_Functions::PHP_DATE_FORMAT); ?>
			</td>
		</tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="timeform" />
		</form>
		<?php
	}
}
?>
