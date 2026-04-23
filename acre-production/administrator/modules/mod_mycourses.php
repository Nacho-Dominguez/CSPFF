<?php
/**
* @version $Id$
* @package Joomla
* @copyright Copyright (C) 2006 Velocera Engineering, LLC.
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

$where = array();
$where[] = "(c.instructor_id='" . $my->id . "' OR c.instructor_2_id='" . $my->id . "')";

$sql = "SELECT c.*, DATE_FORMAT(course_start_date,\"%b %d, %Y %h:%i %p\") AS course_start_date, t.type_name, s.status_name,"
	. " l.location_name, l.state"
	. "\n FROM #__course c"
	. "\n LEFT JOIN #__location l USING (`location_id`)"
	. "\n LEFT JOIN #__course_type t ON (c.`course_type_id` = t.`type_id`)"
	. "\n LEFT JOIN #__course_status s ON (c.`status_id` = s.`status_id`)"
	. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
	. "\n ORDER BY c.course_start_date DESC"
	;
$database->setQuery( $sql );
$rows = $database->loadObjectList();
if ($database->getErrorNum()) {
	echo $database->stderr();
	return false;
}
//echo str_replace('#_','jos',$sql);
?>

<table class="adminlist">
<tr>
	<th class="title">
		Type
	</th>
	<th class="title">
		Status
	</th>
	<th class="title">
		Date/Time
	</th>
	<th class="title">
		Location
	</th>
</tr>
<?php
if (!count($rows)) {
	echo '<tr><td colspan="4">You are not scheduled to teach any upcoming courses.</td></tr>';
}

foreach ($rows as $row) {
	$link = 'index2.php?option=com_course&amp;task=viewA&amp;id='. $row->course_id;
	?>
	<tr>
		<td>
			<?php echo $row->type_name;?>
		</td>
		<td>
			<?php echo $row->status_name;?>
		</td>
		<td>
			<a href="<?php echo $link; ?>">
				<?php echo $row->course_start_date;?></a>
		</td>
		<td>
			<?php echo $row->location_name;?>
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