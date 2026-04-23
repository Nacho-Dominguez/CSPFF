<?php
/**
* @version $Id: admin.users.html.php 3513 2006-05-15 20:52:25Z stingrey $
* @package Joomla
* @subpackage Users
* @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

/**
* @package Joomla
* @subpackage Users
*/
class HTML_users {

	function showUsers( &$rows, $pageNav, $search, $option, $lists ) {
		?>
		<script type="text/javascript">
		function resetFilters() {
			var inputs = document.adminForm.getElementsByTagName("input");
			for (var x=0;x!=inputs.length;x++){
				var name = inputs[x].name;
				if (name == 'search' || name.indexOf('ilter_') == 1) {
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
			<th class="user">User Manager</th>
			<td>Filter:</td>
			<td><input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" /></td>
      <?php self::renderFilters($lists) ?>
			<td><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();" /></td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="2%" class="title">
			#
			</th>
			<th width="3%" class="title">
			<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th class="title">
			Name
			</th>
			<th width="5%" class="title" nowrap="nowrap">
			Logged In
			</th>
			<th width="5%" class="title">
			Active
			</th>
			<th width="15%" class="title">
			Group
			</th>
      <?php self::fireAddColumnHeader() ?>
			<th width="15%" class="title">
			E-Mail
			</th>
			<th width="10%" class="title">
			Last Visit
			</th>
			<th width="1%" class="title">
			ID
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row 	=& $rows[$i];

			$img 	= $row->block ? 'publish_x.png' : 'tick.png';
			$task 	= $row->block ? 'unblock' : 'block';
			$alt 	= $row->block ? 'Enabled' : 'Blocked';
			$link 	= 'index2.php?option=com_users&amp;task=editA&amp;id='. $row->id. '&amp;hidemainmenu=1';
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $i+1+$pageNav->limitstart;?>
				</td>
				<td>
				<?php echo mosHTML::idBox( $i, $row->id ); ?>
				</td>
				<td>
				<a href="<?php echo $link; ?>">
				<?php echo $row->name; ?>
				</a>
				</td>
				<td align="center">
				<?php echo $row->loggedin ? '<img src="images/tick.png" width="12" height="12" border="0" alt="" />': ''; ?>
				</td>
				<td>
				<a href="javascript: void(0);" onClick="return listItemTask('cb<?php echo $i;?>','<?php echo $task;?>')">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</a>
				</td>
				<td>
				<?php echo $row->groupname; ?>
				</td>
        <?php self::fireAddColumn($row) ?>
				<td>
				<a href="mailto:<?php echo $row->email; ?>">
				<?php echo $row->email; ?>
				</a>
				</td>
				<td nowrap="nowrap">
				<?php echo mosFormatDate( $row->lastvisitDate, _CURRENT_SERVER_TIME_FORMAT ); ?>
				</td>
				<td>
				<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function edituser( &$row, &$contact, &$lists, $option, $uid, &$params ) {
		global $my, $acl;
		global $mosConfig_live_site;

		mosCommonHTML::loadOverlib();
		$canBlockUser 	= $acl->acl_check( 'administration', 'edit', 'users', $my->usertype, 'user properties', 'block_user' );
		$canEmailEvents = $acl->acl_check( 'workflow', 'email_events', 'users', $acl->get_group_name( $row->gid, 'ARO' ) );
		?>
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

			// do field validation
			if (trim(form.name.value) == "") {
				alert( "You must provide a name." );
				form.name.focus();
				form.name.select();
			} else if (trim(form.email.value) == "") {
				alert( "You must provide an email address." );
				form.email.focus();
				form.email.select();
			} else if (! form.email.value.match(re) || form.email.value.length < 3) {
				alert( "The e-mail address contains invalid characters or is too short." );
				form.email.focus();
				form.email.select();
			} else if (form.password && trim(form.password.value) != "" && (!form.password.value.match(/[A-Z]/g) || !form.password.value.match(/[a-z]/g) || !form.password.value.match(/[0-9]/g) || form.password.value.length < 7)) {
				alert( "Password must be at least 7 characters long, and contain at least one capital letter, one lowercase letter, and one number." );
			} else if (form.password && trim(form.password.value) != "" && form.password.value != form.password2.value){
				alert( "Password do not match." );
			} else if (!getSelectedValue('adminForm','state')) {
				alert( "Please choose a state." );
				form.state.focus();
			} else if (form.gid.value < 1) {
				alert( "Please choose a group." );
			} else if (form.gid.value == "29") {
				alert( "Please Select another group as `Public Frontend` is not a selectable option" );
			} else if (form.gid.value == "30") {
				alert( "Please Select another group as `Public Backend` is not a selectable option" );
			} else {
				form.username.value = form.email.value;
				turnon('currLocadminLocs');
				turnon('currInstLocs');
				turnon('currCourtadminLocs');
				submitform( pressbutton );
			}
		}

		function gotocontact( id ) {
			var form = document.adminForm;
			form.contact_id.value = id;
			submitform( 'contact' );
		}
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<input type="hidden" name="username" value="<?php echo $row->email; ?>" />

		<table class="adminheading">
		<tr>
			<th class="user">
			User: <small><?php echo $row->id ? 'Edit' : 'Add';?></small>
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">
					User Details
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
					<td width="130">
					Name: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="name" class="inputbox" size="40" value="<?php echo $row->name; ?>" maxlength="50" />
					</td>
				</tr>
				<tr>
					<td>
					Control Number:
					</td>
					<td>
					<input type="text" name="nsc" size="10" maxlength="10" class="inputbox" value="<?php echo $row->nsc;?>" /> <?php echo mosToolTip('Only applies to instructors.','Control/NSC Instructor Number'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Instructor Number:
					</td>
					<td>
					<input type="text" name="control" size="10" maxlength="10" class="inputbox" value="<?php echo $row->control;?>" /> <?php echo mosToolTip('Only applies to instructors.','DOR Instructor Number'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Single Instructor Fee:
					</td>
					<td>
					<input type="text" name="single_fee" size="10" maxlength="10" class="inputbox" value="<?php echo $row->single_fee;?>" /> <?php echo mosToolTip('This is the fee the instructor receives when they are the only instructor assigned to teach a course.','Single Instructor Fee'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Multiple Instructor Fee:
					</td>
					<td>
					<input type="text" name="multiple_fee" size="10" maxlength="10" class="inputbox" value="<?php echo $row->multiple_fee;?>" /> <?php echo mosToolTip('This is the fee the instructor receives when there is more than one instructor assigned to teach a course.','Multiple Instructor Fee'); ?>
					</td>
				</tr>
				<tr>
					<td>
					Email/Username: <span class="required">&#149;</span>
					</td>
					<td nowrap="nowrap">
					<input class="inputbox" type="text" name="email" size="40" value="<?php echo $row->email; ?>" /> <?php echo mosToolTip('Changing a user e-mail address will also change their username to log on to the site.','Warning!',null,'warning.png'); ?>
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
					Work Phone:
					</td>
					<td>
					<input type="text" id="work_phone" name="work_phone" size="15" maxlength="15" class="inputbox" value="<?php echo $row->work_phone;?>" onBlur="fixPhone(this)" /> &nbsp; x <input type="text" name="work_ext" size="6" maxlength="6" class="inputbox" value="<?php echo $row->work_ext;?>" />
					</td>
				</tr>
				<tr>
					<td>
					Home Phone:
					</td>
					<td>
					<input type="text" id="home_phone" name="home_phone" size="15" maxlength="15" class="inputbox" value="<?php echo $row->home_phone;?>" onBlur="fixPhone(this)" />
					</td>
				</tr>
				<tr>
					<td>
					New Password:
					</td>
					<td>
					<input class="inputbox" type="password" name="password" size="40" value="" />
					</td>
				</tr>
				<tr>
					<td>
					Verify Password:
					</td>
					<td>
					<input class="inputbox" type="password" name="password2" size="40" value="" />
					</td>
				</tr>
				<tr>
					<td valign="top">
					Group: <span class="required">&#149;</span>
					</td>
					<td>
					<?php echo $lists['gid']; ?>
					</td>
				</tr>
				<?php
				self::fireAfterGroup($row);
				
				if ($canBlockUser) {
					?>
					<tr>
						<td>
						Block User: <span class="required">&#149;</span>
						</td>
						<td>
						<?php echo $lists['block']; ?>
						</td>
					</tr>
					<?php
				}
				if ($canEmailEvents) {
					?>
					<tr>
						<td>
						Receive System Emails:
						</td>
						<td>
						<?php echo $lists['sendEmail']; ?>
						</td>
					</tr>
					<?php
				}
				if( $uid ) {
					?>
					<tr>
						<td>
						Register Date:
						</td>
						<td>
						<?php echo $row->registerDate;?>
						</td>
					</tr>
				<tr>
					<td>
					Last Visit Date:
					</td>
					<td>
					<?php echo $row->lastvisitDate;?>
					</td>
				</tr>
					<?php
				}
				?>
				<tr>
					<td colspan="2">&nbsp;

					</td>
				</tr>
				</table>
			</td>
			<td width="10">&nbsp;</td>
			<td valign="top" width="60%">
				<table class="adminform">
				<?php if ($row->gid == 27) { ?>
				<tr>
					<th colspan="2">Location Administrator Permissions</th>
				</tr>
				<tr>
					<td colspan="2">
						As an location administrator, this user may manage the following location(s). Giving administration abilities for a location parent grants administration abilities for all its children.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Locations</strong><br />
						<?php echo $lists['availLocadminLocs']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availLocadminLocs'), $('currLocadminLocs'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Locations</strong><br />
						<?php echo $lists['currLocadminLocs']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currLocadminLocs'), $('availLocadminLocs'));" />
					</td>
				</tr>
				<?php } ?>
				<?php if (in_array($row->gid,array(26,27))) { ?>
				<tr>
					<th colspan="2">Instructor Permissions</th>
				</tr>
				<tr>
					<td colspan="2">
						As an instructor, this user may manage courses and be assigned to instruct courses at the following location(s).
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Locations</strong><br />
						<?php echo $lists['availInstLocs']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availInstLocs'), $('currInstLocs'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Locations</strong><br />
						<?php echo $lists['currInstLocs']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currInstLocs'), $('availInstLocs'));" />
					</td>
				</tr>
				<?php } ?>
				<?php if (in_array($row->gid,array(23,26,27))) { ?>
				<tr>
					<th colspan="2">Court Administrator Permissions</th>
				</tr>
				<tr>
					<td colspan="2">
						As a court administrator, this user may manage the following courts.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>
					</td>
				</tr>
				<tr>
					<td style="text-align:right; padding:0px 10px 20px 0px;" width="50%">
						<strong>Available Courts</strong><br />
						<?php echo $lists['availCourtadminLocs']; ?><br />
						<input type="button" value="Add --&gt;" onclick="moveOptions($('availCourtadminLocs'), $('currCourtadminLocs'));" />
					</td>
					<td style="padding:0px 0px 20px 10px;" width="50%">
						<strong>Current Courts</strong><br />
						<?php echo $lists['currCourtadminLocs']; ?><br />
						<input type="button" value="&lt;-- Remove" onclick="moveOptions($('currCourtadminLocs'), $('availCourtadminLocs'));" />
					</td>
				</tr>
				<?php } ?>
				<?php if ($row->gid ==0) { ?>
				<tr>
					<th colspan="2">Administration Permissions</th>
				</tr>
				<tr>
					<td colspan="2">
						Administration permissions, such as instructor locations and court administrator courts, may be assigned after the user is saved.
					</td>
				</tr>
				<?php } ?>
				</table>
			</td>
		</tr>
		</table>

		<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="contact_id" value="" />
		<?php
		if (!$canEmailEvents) {
			?>
			<input type="hidden" name="sendEmail" value="0" />
			<?php
		}
		?>
		</form>
		<?php
	}
	
	private static function renderFilters($lists)
	{
    foreach ($lists as $list)
    {
      echo '<td>' . $list . '</td>';
    }
	}
	
	private static function fireAfterGroup($row)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_AddUserFields) {
				$listener->afterGroup($row);
			}
		}
	}
	
	private static function fireAddColumn($record)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_ShowUsers) {
				$listener->addColumn($record);
			}
		}
	}
	
	private static function fireAddColumnHeader()
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_ShowUsers) {
				$listener->addColumnHeader();
			}
		}
	}
}
