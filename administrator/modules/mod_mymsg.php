<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2006 Velocera Engineering, LLC.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$query = "SELECT a.*, u.name AS user_from"
. "\n FROM #__messages AS a"
. "\n INNER JOIN #__users AS u ON u.id = a.user_id_from"
. "\n WHERE a.user_id_to='" . $my->id . "'"
. "\n ORDER BY date_time DESC"
. "\n LIMIT 100"
;
$database->setQuery( $query );

$rows = $database->loadObjectList();
if ($database->getErrorNum()) {
	echo $database->stderr();
	return false;
}
?>

<table class="adminlist">
<tr>
	<th class="title">
		Subject
	</th>
	<th class="title">
		From
	</th>
	<th class="title">
		Date
	</th>
	<th class="title">
		Read
	</th>
</tr>
<?php
if (!count($rows)) {
	echo '<tr><td colspan="4">You have no messages in your inbox.</td></tr>';
}

foreach ($rows as $row) {
	$link = 'index2.php?option=com_messages&amp;task=viewA&amp;hidemainmenu=1&amp;id='. $row->message_id;
	?>
	<tr>
		<td>
			<a href="<?php echo $link; ?>">
				<?php echo htmlspecialchars($row->subject, ENT_QUOTES);?></a>
		</td>
		<td>
			<?php echo $row->user_from;?>
		</td>
		<td>
			<?php echo $row->date_time;?>
		</td>
		<td>
			<?php
			if (intval( $row->state ) == "1") {
				echo 'Read';
			} else {
				echo 'Unread';
			}
			?>
		</td>
	</tr>
	<?php
}
?>
<tr>
	<th colspan="4">
	</th>
</tr>
</table>