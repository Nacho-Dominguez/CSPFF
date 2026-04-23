<?php

class HTML_messages {
	function showMessages( &$rows, $pageNav, $search, $option ) {
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="inbox">
				Private Messaging
			</th>
			<td>
				Search:
			</td>
			<td>
				<input type="text" name="search" value="<?php echo $search;?>" class="inputbox" onChange="document.adminForm.submit();" />
			</td>
		</tr>
		</table>

		<table class="adminlist">
		<tr>
			<th width="20">
				#
			</th>
			<th width="5%" class="title">
				<input type="checkbox" name="toggle" value="" onClick="checkAll(<?php echo count($rows); ?>);" />
			</th>
			<th width="60%" class="title">
				Subject
			</th>
			<th width="15%" class="title">
				From
			</th>
			<th width="15%" class="title">
				Date
			</th>
			<th width="5%" class="title">
				Read
			</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count( $rows ); $i < $n; $i++) {
			$row =& $rows[$i];
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td width="20">
					<?php echo $i+1+$pageNav->limitstart;?>
				</td>
				<td width="5%">
					<?php echo mosHTML::idBox( $i, $row->message_id ); ?>
				</td>
				<td width="60%">
					<a href="#edit" onClick="hideMainMenu();return listItemTask('cb<?php echo $i;?>','view')">
						<?php echo $row->subject; ?></a>
				</td>
				<td width="15%">
					<?php echo $row->user_from; ?>
				</td>
				<td width="15%">
					<?php echo $row->date_time; ?>
				</td>
				<td width="15%">
					<?php
					if (intval( $row->state ) == "1") {
						echo 'Read';
					} else {
						echo 'Unread';
					}
					?>
				</td>
			</tr>
			<?php $k = 1 - $k;
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

	function editConfig( &$vars, $option) {
		$tabs = new mosTabs(0);
		?>
		<form action="index2.php" method="post" name="adminForm">
		<script language="javascript" type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'saveconfig') {
				if (confirm ("Are you sure?")) {
					submitform( pressbutton );
				}
			} else {
				document.location.href = 'index2.php?option=<?php echo $option;?>';
			}
		}
		</script>

		<table class="adminheading">
		<tr>
			<th class="msgconfig">
				Private Messaging Configuration
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td width="25%">
				Mail me on new Message:
			</td>
			<td>
				<?php echo $vars['mail_on_new']; ?>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		</form>
		<?php
	}

	function viewMessage( &$row, $option ) {
		?>
		<form action="index2.php" method="post" name="adminForm">

		<table class="adminheading">
		<tr>
			<th class="inbox">
				View Private Message
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<td width="100">
				From:
			</td>
			<td width="85%" bgcolor="#ffffff">
				<?php echo $row->user_from;?>
			</td>
		</tr>
		<tr>
			<td>
				Posted:
			</td>
			<td bgcolor="#ffffff">
				<?php echo $row->date_time;?>
			</td>
		</tr>
		<tr>
			<td>
				Subject:
			</td>
			<td bgcolor="#ffffff">
				<?php echo $row->subject;?>
			</td>
		</tr>
		<tr>
			<td valign="top">
				Message:
			</td>
			<td width="100%" bgcolor="#ffffff">
				<pre><?php echo htmlspecialchars( $row->message );?></pre>
			</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option;?>" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="1" />
		<input type="hidden" name="cid[]" value="<?php echo $row->message_id; ?>" />
		<input type="hidden" name="userid" value="<?php echo $row->user_id_from; ?>" />
		<input type="hidden" name="subject" value="Re: <?php echo $row->subject; ?>" />
		<input type="hidden" name="hidemainmenu" value="0" />
		</form>
		<?php
	}

	function newMessage($option, $recipientslist, $subject ) {
		global $my;
		
		$htmlHead = A25_DI::HtmlHead();
		
		$htmlHead->includeJquery();
		
		$htmlHead->append('
		<script type="text/javascript">
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == "cancel") {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if (form.subject.value == "") {
				alert( "You must provide a subject." );
			} else if (form.message.value == "") {
				alert( "You must provide a message." );
			} else if ($("#cid option:selected").length < 1) {
				alert( "You must select at least one recipient." );
			} else {
				submitform( pressbutton );
			}
		}');
		
		// These javascript functions can be passed either a single value or an
		// array.  The values passed in will be selected, in addition to
		// whatever was already selected.
		$htmlHead->append('
		function selectRecipient(user_id) {
			already_selected = $("#cid").val();
			if (already_selected) {
				all_to_select = already_selected.concat(user_id);
			} else {
				all_to_select = user_id;
			}
			$("#cid").val(all_to_select);
		}
		function removeRecipient(user_ids) {
			all_to_select = new Array();
			
			already_selected = $("#cid").val();
			
			for (var i = 0; i < already_selected.length; i++) {
				if ($.inArray(already_selected[i], user_ids) == -1) {
					all_to_select.push(already_selected[i]);
				}
			}
			$("#cid").val(all_to_select);
		}
		</script>
		');
		?>

		<table class="adminheading">
		<tr>
			<th class="inbox">
				New Private Message
			</th>
		</tr>
		</table>

		<form action="index2.php" method="post" name="adminForm">
		<table class="adminform">
		<tr>
			<td width="100">
				To:
			</td>
			<td width="85%">
				<table><tr valign="top"><td><?php echo $recipientslist;?></td>
				<td>
					<?php if (A25_DI::User()->isAdminOrHigher()) { ?>
					<h4>Add everyone from a user-type:</h4>
					<?php echo self::linksForGroupAutoselection();
					} ?>
				</td></tr></table>
			</td>
		</tr>
		<tr>
			<td>
				Subject:
			</td>
			<td>
				<input type="text" name="subject" size="50" maxlength="100" class="inputbox" style="width:400px" value="<?php echo $subject; ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top">
				Message:
			</td>
			<td width="100%">
				<textarea name="message"  id="message" style="width:400px" rows="20" class="inputbox"></textarea>
			</td>
		</tr>
		</table>

		<input type="hidden" name="user_id_from" value="<?php echo $my->id; ?>">
		<input type="hidden" name="option" value="<?php echo $option; ?>">
		<input type="hidden" name="task" value="">
		</form>
		<?php
	}
	
	private static function linksForGroupAutoselection()
	{
		$return = '';
		foreach(self::allUsertypes() as $usertype)
		{
			$user_ids = self::userIdsForGroup($usertype);
			
			$return .= $usertype .': <a href="#" onclick="selectRecipient('
					. $user_ids
					. ')">select</a> - '
					. '<a href="#" onclick="removeRecipient('
					. $user_ids . ')">unselect</a><br/>';
		}
		return $return;
	}
	
	protected static function userIdsForGroup($group_name)
	{
		// If the test suite for this function ever grows to larger than 1 test,
		// this query should be extracted into its own function, so that the
		// tests for this function can become true 'unit' tests.
		$users = self::usersForGroup($group_name);
		
		$user_ids = array();
		foreach($users as $user) {
			$user_ids[] = $user->id;
		}
		
		return '[' . implode(',', $user_ids) . ']';
	}
	
	protected static function usersForGroup($group_name)
	{
		$generated_by_plugin = self::fireUsersForGroup($group_name);
		if ($generated_by_plugin)
			return $generated_by_plugin;
		
		return Doctrine_Query::create()
			->from('A25_Record_User u')
			->where('u.usertype = ?', $group_name)
			->execute();
	}
	private static function fireUsersForGroup($group_name)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_AddEmailGroups) {
				$users = $listener->usersForGroup($group_name);
				if ($users)
					return $users;
			}
		}
	}
	
	protected static function allUsertypes()
	{
		$dbh = Doctrine_Manager::connection()->getDbh();
		$stmt = $dbh->prepare('SELECT DISTINCT(usertype) FROM jos_users');
		$stmt->execute();
		$usertypes = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
		$usertypes = self::fireAllUsertypes($usertypes);
		return $usertypes;
	}
	
	private static function fireAllUsertypes($usertypes)
	{
		foreach (A25_ListenerManager::all() as $listener) {
			if ($listener instanceof A25_ListenerI_AddEmailGroups) {
				$usertypes = $listener->modifyUsertypes($usertypes);
			}
		}
		return $usertypes;
	}
}