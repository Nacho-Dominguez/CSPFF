<?php

class HTML_location {

	function listLocation( &$rows, &$pageNav, $search, $option, &$lists ) {
		global $my, $mosConfig_offset, $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
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
			<th>List Locations</th>
			<td nowrap="nowrap">Filter by Name:</td>
			<td><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" /></td>
			<td><?php echo $lists['filter_parent'];?></td>
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
			<th class="title">Contact</th>
			<th class="title">Address</th>
			<th class="title">City</th>
			<th>State</th>
			<th>Zip</th>
			<th>Locate</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$row->id = $row->location_id;

			if (checkPerms($row->location_id, 'edit')) {
				$link 	= 'index2.php?option=com_location&task=editA&hidemainmenu=1&id='. $row->id;
			} else {
				$link = '';
			}

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
				if ( empty($link) || ( $row->checked_out && ( $row->checked_out != $my->id ) ) ) {
					echo $row->location_name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Location">
					<?php echo $row->location_name; ?>
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
				<?php echo $row->location_id; ?>
				</td>
				<?php if ($row->is_location) { ?>
					<td>
					<?php echo $row->contact; ?>
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
					<td align="center">
					<a href="http://maps.google.com/maps?oi=map&q=<?php echo urlencode($row->address_1 . ', ' . $row->city . ',' . $row->state . ' ' . $row->zip); ?>" target="_blank"><img src="<?php echo $mosConfig_live_site; ?>/includes/js/ThemeOffice/tooltip.png" border="0" /></a>
					</td>
				<? } else { ?>
					<td>-</td>
					<td>-</td>
					<td>-</td>
					<td align="center">-</td>
					<td align="center">-</td>
					<td align="center">-</td>
				<? } ?>
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

	function editLocation( A25_Record_LocationAbstract $location, $lists, $option ) {
		mosCommonHTML::loadOverlib();

		mosMakeHtmlSafe( $location );
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
			if (form.location_name.value == "") {
				alert( "You must provide a location name." );
			} else if (!getSelectedValue('adminForm','state')) {
				alert( "You must choose a state." );
			} else {
				turnon('currAdmins');
				turnon('currInsts');
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Location:
			<small>
			<?php echo $location->location_id ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Location Details
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
					Location ID:
					</td>
					<td>
					<?php echo $location->location_id ? $location->location_id : '<i>Automatically assigned after save.</i>';?>
					</td>
				</tr>
				<tr>
					<td>
					Location Name: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="location_name" size="30" maxlength="80" class="inputbox" value="<?php echo $location->location_name;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Address 1: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="address_1" size="30" maxlength="255" class="inputbox" value="<?php echo $location->address_1;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Address 2:
					</td>
					<td>
					<input type="text" name="address_2" size="30" maxlength="255" class="inputbox" value="<?php echo $location->address_2;?>" />
					</td>
				</tr>
				<tr>
					<td>
					City: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="city" size="30" maxlength="80" class="inputbox" value="<?php echo $location->city;?>" />
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
					<input type="text" name="zip" size="10" maxlength="10" class="inputbox" value="<?php echo $location->zip;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Contact (for students):
					</td>
					<td>
					<input type="text" name="contact" size="30" maxlength="255" class="inputbox" value="<?php echo $location->contact;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Phone:
					</td>
					<td>
					<input type="text" name="phone" size="20" maxlength="30" class="inputbox" value="<?php echo $location->phone;?>" onBlur="fixPhone(this)" />
					</td>
				</tr>
				<tr>
					<td>
					# of seats:
					</td>
					<td>
					<input type="text" name="number_of_seats" size="3" maxlength="8" class="inputbox" value="<?php echo $location->number_of_seats;?>" />
					<?php echo mosToolTip('If set, all courses at this location will have their Course Capacity set to this by default. The Course Capacity for an individual course can still be changed to a different value if necessary.','# of seats'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo PlatformConfig::schoolType ?>?
					</td>
					<td>
					<?php echo $lists['is_highschool']; ?>
					</td>
				</tr>
				<tr>
					<td>
					Virtual?
					</td>
					<td>
					<?php echo $lists['virtual']; ?>
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
					<td>
					Location Parent: <span class="required">&#149;</span>
					</td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">
						<b>Override settings inherited from Location Parent:</b><br/>
						<i>(leave these blank unless this location has special,
						unique rules)</i>
					</td>
				</tr>
			<?php if ($location->exists()) {
				$locationParent = $location->settingParent();
				if (!$locationParent) {
					$locationParent = new A25_Record_LocationParent();
				}
				?>
				<tr>
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td></td><td>Inherited Value</td><td>Override With</td>
				</tr>
				<tr>
					<td>
					Tuition for Non-Court-Ordered:
					</td>
					<td>$<?php echo $locationParent->getSetting('fee') ?></td>
					<td>
					$ <input type="text" name="fee" size="10" maxlength="10" class="inputbox" value="<?php echo $location->fee ?>" />  <?php echo mosToolTip('This is the tuition fee that all volunteer students pay (those who are not taking the class because of a court order or pending legal matter).','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Tuition for Court-Ordered:
					</td>
					<td>$<?php echo PlatformConfig::defaultCourtFee ?></td>
					<td>To change, <a href="mailto:jonathan@appdevl.net">contact Webmaster</a></td>
				</tr>
				<tr>
					<td>
					Late Payment Fee:
					</td>
					<td>$<?php echo $locationParent->getSetting('late_fee'); ?></td>
					<td>
					$ <input type="text" name="late_fee" size="10" maxlength="10" class="inputbox" value="<?php echo $location->late_fee ?>" />  <?php echo mosToolTip('This fee is charged to students who pay after the Late Fee Deadline.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Payment Option Deadline:
					</td>
					<td><?php echo $locationParent->getSetting('register_cc_days') ?> Days</td>
					<td>
					<input type="text" name="register_cc_days" size="10" maxlength="10" class="inputbox" value="<?php echo $location->register_cc_days ?>" /> Days before course<?php echo mosToolTip('Require immediate payment via credit card if a student enrolls in the course after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Cancellation Deadline:
					</td>
					<td><?php echo $locationParent->getSetting('cancellation_deadline') ?> Hours</td>
					<td>
					<input type="text" name="cancellation_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $location->cancellation_deadline ?>" /> Hours before course <?php echo mosToolTip('Students can no longer cancel an enrollment after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Enrollment Deadline:
					</td>
					<td><?php echo $locationParent->getSetting('enrollment_deadline') ?></td>
					<td>
					<input type="text" name="enrollment_deadline" size="20" maxlength="25" class="inputbox" value="<?php echo $location->enrollment_deadline ?>" />  before course (Include the unit of time, e.g. "12 hours" or "2 days") <?php echo mosToolTip('Students can no longer enroll in the course after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Late Fee Deadline:
					</td>
					<td><?php echo $locationParent->getSetting('late_fee_deadline') ?> Hours</td>
					<td>
					<input type="text" name="late_fee_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $location->late_fee_deadline ?>" /> Hours before course <?php echo mosToolTip('Students are charged the Late Payment Fee when paying after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Payment Deadline:
					</td>
					<td><?php echo $locationParent->getSetting('payment_deadline') ?> Hours</td>
					<td>
					<input type="text" name="payment_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $location->payment_deadline ?>" /> Hours before course <?php echo mosToolTip('Students are not allowed to make a payment after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<?php self::fireAfterLocationEditForm($location); ?>
				</tr>
						</table>
					</td>
				</tr>
			<?php } else { ?>
				<tr>
					<td colspan="2"><i>Available after initial save</i></td>
				</tr>
			<?php } ?>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="10">&nbsp;</td>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th colspan="2">Location Description</th>
				</tr>
					<td colspan="2">
						The following description may include a description of the location, driving directions, FAQ items, and more.
					</td>
				<tr>
					<td colspan="2">
						<?php
						// parameters : areaname, content, hidden field, width, height, cols, rows
						editorArea( 'editor1', $location->description, 'description', '100%;', '200', '50', '5' ) ;
						?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
          <th colspan="2">Administrative Notes</th>
				</tr>
				<tr>
          <td colspan="2">Notes not visible to students, such as contact information for the location manager, can be placed here.</td>
				</tr>
				<tr>
					<td colspan="2">
					<textarea name="admin_notes" class="text_area" rows="5" cols="80"><?php echo $location->admin_notes;?></textarea>
					</td>
				</tr>
				<tr>
					<th colspan="2">Location Administrators</th>
				</tr>
				<tr>
					<td colspan="2">
						The following users have permissions to manage this location.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Location Administrators</strong><br />
						<?php echo $lists['availAdmins']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availAdmins'), $('currAdmins'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Location Administrators</strong><br />
						<?php echo $lists['currAdmins']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currAdmins'), $('availAdmins'));" />
					</td>
				</tr>
				<tr>
					<th colspan="2">Instructors</th>
				</tr>
					<td colspan="2">
						The following users have permissions to teach courses at this location.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Instructors</strong><br />
						<?php echo $lists['availInsts']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availInsts'), $('currInsts'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Instructors</strong><br />
						<?php echo $lists['currInsts']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currInsts'), $('availInsts'));" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>


		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="location_id" value="<?php echo $location->location_id; ?>" />
		<input type="hidden" name="is_location" value="1" />
		<input type="hidden" name="created" value="<?php echo $location->created; ?>" />
		<input type="hidden" name="created_by" value="<?php echo $location->created_by; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}

	function fireAfterLocationEditForm(A25_Record_LocationAbstract $location)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_AdminUi) {
				$listener->afterLocationEditForm($location);
			}
		}
	}


	function editLocationParent( &$row, &$lists, $option ) {
		mosCommonHTML::loadOverlib();
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
			if (form.location_name.value == "") {
				alert( "You must provide a location name." );
			} else {
				turnon('currAdmins');
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm" enctype="multipart/form-data">
		<table class="adminheading">
		<tr>
			<th>
			Location Parent:
			<small>
			<?php echo $row->location_id ? 'Edit' : 'New';?>
			</small>
			</th>
		</tr>
		</table>

		<table width="100%" border="0">
		<tr>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Location Parent Details
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
					Location ID:
					</td>
					<td>
					<?php echo $row->location_id ? $row->location_id : '<i>Automatically assigned after save.</i>';?>
					</td>
				</tr>
				<?php if ($row->location_id != 1) { ?>
				<tr>
					<td>
					Location Parent: <span class="required">&#149;</span>
					</td>
					<td>
					<?php echo $lists['parent']; ?>
					</td>
				</tr>
				<?php } else { ?>
				<input type="hidden" name="parent" value="0" />
				<?php } ?>
				<tr>
					<td>
					Location Name: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="location_name" size="30" maxlength="80" class="inputbox" value="<?php echo $row->location_name;?>" />
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
					<td>
					Tuition for Non-Court-Ordered:
					</td>
					<td>
					$ <input type="text" name="fee" size="10" maxlength="10" class="inputbox" value="<?php echo $row->fee; ?>" />  <?php echo mosToolTip('This is the tuition fee that all volunteer students pay (those who are not taking the class because of a court order or pending legal matter).','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Tuition for Court-Ordered:
					</td>
					<td>$<?php echo PlatformConfig::defaultCourtFee ?> (To change, <a href="mailto:<?php echo PlatformConfig::webmasterEmail?>">contact Webmaster</a>)</td>
				</tr>
				<tr>
					<td>
					Late Payment Fee:
					</td>
					<td>
					$ <input type="text" name="late_fee" size="10" maxlength="10" class="inputbox" value="<?php echo $row->late_fee;?>" />  <?php echo mosToolTip('This fee will be charged to students who pay after the Late Fee Deadline.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Payment Option Deadline:
					</td>
					<td>
					<input type="text" name="register_cc_days" size="10" maxlength="10" class="inputbox" value="<?php echo $row->register_cc_days;?>" /> Days before course <?php echo mosToolTip('Require immediate payment via credit card if a student enrolls in the course after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Cancellation Deadline:
					</td>
					<td>
					<input type="text" name="cancellation_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $row->cancellation_deadline ?>" /> Hours before course <?php echo mosToolTip('Students can no longer cancel an enrollment after X hours before course start time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Enrollment Deadline:
					</td>
					<td>
					<input type="text" name="enrollment_deadline" size="20" maxlength="25" class="inputbox" value="<?php echo $row->enrollment_deadline ?>" /> before course (Include the unit of time, e.g. "12 hours" or "2 days") <?php echo mosToolTip('Students can no longer enroll in the course after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Late Fee Deadline:
					</td>
					<td>
					<input type="text" name="late_fee_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $row->late_fee_deadline ?>" /> Hours before course <?php echo mosToolTip('Students are charged the Late Payment Fee when paying after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Payment Deadline:
					</td>
					<td>
					<input type="text" name="payment_deadline" size="10" maxlength="10" class="inputbox" value="<?php echo $row->payment_deadline ?>" /> Hours before course <?php echo mosToolTip('Students are not allowed to make a payment after this time.','What is this?'); ?>
					</td>
				</tr>
				<tr>
					<td colspan="3">
					</td>
				</tr>
				</table>
			</td>
			<td width="10" rowspan="2">&nbsp;</td>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th colspan="2">Location Parent Administrators</th>
				</tr>
				<tr>
					<td colspan="2">
						The following users have permissions to manage this location.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Location Administrators</strong><br />
						<?php echo $lists['availAdmins']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availAdmins'), $('currAdmins'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Location Administrators</strong><br />
						<?php echo $lists['currAdmins']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currAdmins'), $('availAdmins'));" />
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<table class="adminform">
					<tr>
						<th colspan="2">Enrollment Email / Receipt:</th>
					</tr>
						<td colspan="2">
							Each Location may have its own customized email sent out to Registrants upon enrolling in a course.
							If this is left blank, the default content will be used.  This text is also what students will see
							on the 'receipt' page of the website, once they enroll.
						</td>
					<tr>
						<td colspan="2">
						    <b>Subject</b><br>
						    <input type="text" name="enrollment_email_subject" size="60" maxlength="200" class="inputbox" value="<?php echo $row->enrollment_email_subject;?>" />
						    <br><br>
						    <b>Body</b><br>
							<textarea name="enrollment_email_body" cols="55" rows="15"><? echo $row->enrollment_email_body; ?></textarea>
						</td>
					</tr>

					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>
				<table class="adminform">
					<tr>
						<th colspan="2">Course Completed Email:</th>
					</tr>
						<td colspan="2">
							Each Location may have its own customized email sent out to Students upon completing a course.
							If this is left blank, the default content will be used.
						</td>
					<tr>
						<td colspan="2">
						    <b>Subject</b><br>
						    <input type="text" name="course_completed_email_subject" size="60" maxlength="200" class="inputbox" value="<?php echo $row->course_completed_email_subject;?>" />
						    <br><br>
						    <b>Body</b><br>
							<textarea name="course_completed_email_body" cols="55" rows="15"><? echo $row->course_completed_email_body; ?></textarea>
						</td>
					</tr>

					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>
				<!--<table class="adminform">
					<tr>
						<th colspan="2">Payment Reminder Email:</th>
					</tr>
						<td colspan="2">
							Each Location may have its own customized email sent out to Registrants who have not yet paid.
							If this is left blank, the default content will be used.
						</td>
					<tr>
						<td colspan="2">
						    <b>Subject</b><br>
						    <input type="text" name="payment_reminder_email_subject" size="60" maxlength="200" class="inputbox" value="<?php //echo $row->payment_reminder_email_subject;?>" />
						    <br><br>
						    <b>Body</b><br>
							<textarea name="payment_reminder_email_body" cols="55" rows="15"><? //echo $row->payment_reminder_email_body; ?></textarea>
						</td>
					</tr>

					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>-->
			</td>
			<td valign="top">
				<table border="0" width="100%">
                    <tr>
            		    <td valign="top" width="50%">
            				<table class="adminform">
                                <tr>
                                    <th colspan="3">"How did you hear about us?" List</th>
                                </tr>
								<tr>
									<td colspan="3">Note: in order to add an item, you must be in the 'Current Location Administrators' list above.  If you are not, it will log you out of the system.</td>
								</tr>
                                <?php
				if (count($lists['heard']) > 0) {
					foreach ( $lists['heard'] as $heardItem ) { ?>
                                    <tr>
                                        <td><?php echo $heardItem->hear_about_id; ?></td>
                                        <td><?php echo $heardItem->hear_about_name; ?></td>
                                        <td align="right">
                                            <a href="?option=com_location&task=editheard&hidemainmenu=1&hid=<?php echo $heardItem->hear_about_id; ?>&id=<?php echo $row->location_id; ?>">modify</a>
                                            <a href="?option=com_location&task=delheard&hid=<?php echo $heardItem->hear_about_id; ?>&id=<?php echo $row->location_id; ?>" onclick="return (confirm('Are you sure you want to delete that?')) ? true : false;">delete</a>
                                        </td>
                                    </tr>
                                <?php }
				} ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><a href="?option=com_location&task=addheard&hidemainmenu=1&id=<?php echo $row->location_id; ?>">Add list item</a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
            				</table>
            		    </td>
            		    <td>&nbsp;</td>
            		    <td valign="top" width="50%">
            				<table class="adminform">
                                <tr>
                                    <th colspan="3">"Reason for Attending" List</th>
                                </tr>
								<tr>
									<td colspan="3">Note: in order to add an item, you must be in the 'Current Location Administrators' list above.  If you are not, it will log you out of the system.</td>
								</tr>
                                <?php if (count($lists['reason']) > 0) {
					foreach ( $lists['reason'] as $reasonItem ) { ?>
                                    <tr>
                                        <td><?php echo $reasonItem->reason_id; ?></td>
                                        <td><?php echo $reasonItem->reason_name; ?></td>
                                        <td>
                                            <a href="?option=com_location&task=editreason&hidemainmenu=1&rid=<?php echo $reasonItem->reason_id; ?>&id=<?php echo $row->location_id; ?>">modify</a>
                                            <a href="?option=com_location&task=delreason&rid=<?php echo $reasonItem->reason_id; ?>&id=<?php echo $row->location_id; ?>" onclick="return (confirm('Are you sure you want to delete that?')) ? true : false;">delete</a>
                                        </td>
                                    </tr>
                                <?php }
				} ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><a href="?option=com_location&task=addreason&hidemainmenu=1&id=<?php echo $row->location_id; ?>">Add list item</a></td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
            				</table>
            		    </td>
                    </tr>
				</table>
			</td>
		</tr>
		<tr>
		    <td valign="top">
		    </td>
		    <td>&nbsp;</td>
		    <td valign="top">

		    </td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="location_id" value="<?php echo $row->location_id; ?>" />
		<input type="hidden" name="is_location" value="0" />
		<input type="hidden" name="created" value="<?php echo $row->created; ?>" />
		<input type="hidden" name="created_by" value="<?php echo $row->created_by; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}

	function addCreditType( $location, $option, $row=null ) { ?>
	    <form action="index2.php" method="post" name="adminForm" enctype="multipart/form-data">
		<table class="adminheading">
		<tr>
			<th>Location: <?php echo $location->location_name; ?></th>
		</tr>
		</table>
		<table class="adminform" cellpadding="0" cellspacing="0" border="1">
		    <tr>
		        <th colspan="2">Credit Type Details</th>
		    </tr>
		    <tr>
		        <td>Name:</td>
		        <td><input type="text" name="hear_about_name" value="<?php echo ($row) ? $row->hear_about_name : ''; ?>" /></td>
		    </tr>
		</table>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="location_id" value="<?php echo $location->location_id; ?>" />
		<input type="hidden" name="task" value="" />
		<?php if($row) { ?>
		<input type="hidden" name="credt_type_id" value="<?php echo $row->credit_type_id; ?>" />
		<?php } ?>
		</form>
	<?php
    }
}
?>
