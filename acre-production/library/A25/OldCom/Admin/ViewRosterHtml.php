<?php

class A25_OldCom_Admin_ViewRosterHtml
{
	function viewRoster( &$course, $lists, $option ) {
		A25_Javascript::loadOverlib();
		?>
		<table class="adminheading">
		<tr>
			<th>
			Print Roster
			</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<td>
					Course ID:
					</td>
					<td>
					<?php echo $course->course_id;?>
					</td>
				</tr>
				<tr>
					<td>
					Location:
					</td>
					<td>
					<?php echo $course->getLocationName(); ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					Instructor(s):
					</td>
					<td>
					<?php echo ($course->instructor_id > 0) ? $course->Instructor->name : ''; ?>
					<?php echo ($course->instructor_2_id > 0) ? ', ' . $course->Instructor2->name : ''; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
					Course Time:
					</td>
					<td>
					<?php echo $course->timeInfoHtml(); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				</table>
				<form action="index2.php" method="post" name="adminForm" id="adminForm">
				<table class="adminform">
				<tr>
					<th colspan="2">Course Enrollment</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php echo $course->showPrintRoster(0); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				</table>
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="course_id" value="<?php echo $course->course_id; ?>" />
				<input type="hidden" name="location_id" value="<?php echo $course->Location->location_id; ?>" />
				<input type="hidden" name="task" value="applyenroll" />
				</form>
			</td>
		</tr>
		</table>
		<?php
	}

}
