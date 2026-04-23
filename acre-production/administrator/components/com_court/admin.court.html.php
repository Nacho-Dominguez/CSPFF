<?php

class HTML_court {

	function listCourt( &$rows, &$pageNav, $search, $option, &$lists ) {
		global $my;
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
			$('resetbutton').disabled = true;
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>List Courts</th>
			<td>Filter:</td>
			<td><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" /></td>
			<td><?php echo $lists['filter_state'];?></td>
			<td><?php echo $lists['filter_active'];?></td>
			<td><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();" /></td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title">Name</th>
			<th width="5%" class="title" nowrap="true">Active</th>
			<th>ID</th>
			<th>Fee</th>
			<th>Late Fee</th>
			<th class="title">Address</th>
			<th class="title">City</th>
			<th>State</th>
			<th>Zip</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$row->id = $row->court_id;

			$link 	= 'index2.php?option=com_court&task=editA&hidemainmenu=1&id='. $row->id;

			$img 	= $row->published ? 'tick.png' : 'publish_x.png';
			$task 	= $row->published ? 'unpublish' : 'publish';
			$alt 	= $row->published ? 'Published' : 'Unpublished';

			$checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
					echo $row->court_name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Court">
					<?php echo $row->court_name; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td align="center">
				<?php echo $row->court_id; ?>
				</td>
				<td align="center">
				<?php echo intval($row->fee)>0 ? '$' . $row->fee : '-' ; ?>
				</td>
				<td align="center">
				<?php echo intval($row->late_fee)>0 ? '$' . $row->late_fee : '-' ; ?>
				</td>
				<td>
				<?php echo $row->address_1; ?>
				</td>
				<td>
				<?php echo $row->city; ?>
				</td>
				<td align="center">
				<?php echo $row->state; ?>
				</td>
				<td align="center">
				<?php echo $row->zip; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
	}

	function editCourt( &$row, $lists, $option, $locs ) {
		A25_Javascript::loadOverlib();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.court_name.value == "") {
				alert( "You must provide a court name." );
			} else if (!getSelectedValue('adminForm','state')) {
				alert( "Please choose a state." );
			} else {
				turnon('currAdmins');
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Court:
			<small>
			<?php echo $row->court_id ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

		<?php if (array_key_exists($row->parent,$locs) || $locs[0] == 'all') { ?>
		<table width="100%">
		<tr>
			<td valign="top" width="40%">
		<?php } ?>
				<table class="adminform">
				<tr>
					<th colspan="2">
					Details
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
					<td>
					Court ID:
					</td>
					<td>
					<?php echo $row->court_id ? $row->court_id : '<i>Automatically assigned after save.</i>';?>
					</td>
				</tr>
				<tr>
					<td>
					Location Parent: <span class="required">&#149;</span>
					</td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td>
					Court Name: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="court_name" size="30" maxlength="80" class="inputbox" value="<?php echo $row->court_name;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Address 1:
					</td>
					<td>
					<input type="text" name="address_1" size="30" maxlength="255" class="inputbox" value="<?php echo $row->address_1;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Address 2:
					</td>
					<td>
					<input type="text" name="address_2" size="30" maxlength="255" class="inputbox" value="<?php echo $row->address_2;?>" />
					</td>
				</tr>
				<tr>
					<td>
					City:
					</td>
					<td>
					<input type="text" name="city" size="30" maxlength="80" class="inputbox" value="<?php echo $row->city;?>" />
					</td>
				</tr>
				<tr>
					<td>
					State: <span class="required">&#149;</span>
					</td>
					<td align="left">
					<?php echo $lists['state']; ?>
					</td>
				</tr>
				<tr>
					<td>
					Zip:
					</td>
					<td>
					<input type="text" name="zip" size="10" maxlength="10" class="inputbox" value="<?php echo $row->zip;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Phone:
					</td>
					<td>
					<input type="text" name="phone" size="20" maxlength="30" class="inputbox" value="<?php echo $row->phone;?>" onBlur="fixPhone(this)" />
					</td>
				</tr>
				<tr>
					<td>
					Registration Fee:
					</td>
					<td>
					$<input type="text" name="fee" size="10" maxlength="10" class="inputbox" value="<?php echo intval($row->fee)>0 ? $row->fee : '';?>" />  <? echo mosToolTip('Please enter the dollar amount charged to students who are ordered to attend the course by this court.', 'Registration Fee'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Late Registration Fee:
					</td>
					<td>
					$<input type="text" name="late_fee" size="10" maxlength="10" class="inputbox" value="<?php echo intval($row->late_fee)>0 ? $row->late_fee : '';?>" />  <? echo mosToolTip('Please enter the additional dollar amount charged to students who are ordered to attend the course by this court, but enroll within 24 hours of the course start time.', 'Registration Fee'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Published:
					</td>
					<td>
					<?php echo $lists['published']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
					</td>
				</tr>
				</table>
			<?php if ($locs[0] == 'all' || array_key_exists($row->parent,$locs)) { ?>
			</td>
			<td width="10">&nbsp;</td>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th colspan="2">Court Administrators</th>
				</tr>
				<tr>
					<td colspan="2">
						The following users have permissions to manage this court and may view student enrollments referred by this court.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Court Administrators</strong><br />
						<?php echo $lists['availAdmins']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availAdmins'), $('currAdmins'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Court Administrators</strong><br />
						<?php echo $lists['currAdmins']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currAdmins'), $('availAdmins'));" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php } else { ?>
		<input type="hidden" name="parent" value="<?php echo $row->parent; ?>" />
		<?php } ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="court_id" value="<?php echo $row->court_id; ?>" />
		<input type="hidden" name="xref_id" value="<?php echo (mosGetParam( $_GET, 'xref_id' )) ? mosGetParam( $_GET, 'xref_id' ) : ''; ?>" />
		<input type="hidden" name="created" value="<?php echo $row->created; ?>" />
		<input type="hidden" name="created_by" value="<?php echo $row->created_by; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
	
	function otherCourt( $rows, $option ) { ?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>Manage Enterred Courts</th>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th class="title">Student ID</th>
			<th align="left">Student Name</th>
			<th class="title">Court Enterred</th>
			<th class="title">Date Registered</th>
			<th></th>
			<th></th>
		</tr>
		<?php foreach ($rows as $row) { 
		    $other_court_link = "<a href='index2.php?option=com_court&task=new&hidemainmenu=1&xref_id=" . $row->xref_id . "'>Add New Court</a>";    
		    $edit_student_link = "<a href='index2.php?option=com_student&task=studentForm&hidemainmenu=1&id=" . $row->student_id . "'>" . $row->first_name . ' ' . $row->last_name . "</a>";
		?>
		<tr>
		    <td><?php echo $row->student_id; ?></td>
		    <td><?php echo $edit_student_link; ?></td>
		    <td><?php echo $other_court_link; ?></td>
		    <td><?php echo $row->date_registered; ?></td>
		    <td></td>
		    <td></td>
		</tr>
		<?php } ?>
		<tr>
		    <th colspan="6"></th>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
	   <?php 
	}
}
?>
