<?php

class A25_OldCom_Admin_ListCourseHtml
{
	public static function listCourse( $courses, $pageNav, $option, $lists, $my )
	{
		$javascript = <<<EOQ
<script language="javascript" type="text/javascript">
<!--
function unpublishCourse(course_id){
	var ajaxRequest;  // The variable that makes Ajax possible!

	try{
		// Opera 8.0+, Firefox, Safari
		ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	var params = "course_id=" + course_id;
	ajaxRequest.open("POST","
EOQ;
		$javascript .= A25_Link::to('/administrator/unpublish-course') . '");
	ajaxRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajaxRequest.setRequestHeader("Content-length", params.length);
	ajaxRequest.setRequestHeader("Connection", "close");

	ajaxRequest.send(params);
	img = document.getElementById("course" + course_id);
	img.src = "images/publish_x.png";
	img.alt = "Unpublished"
}
//-->
</script>';
		
		A25_DI::HtmlHead()->append($javascript);
		
		mosCommonHTML::loadCalendar();
		?>
		<script type="text/javascript">
		function tog(elem) {
			if ($(elem).style.display == 'none') {
				new Effect.BlindDown(elem, {duration: 0.2});
				$('showsearch').innerHTML = '(hide filters)';
			} else {
				new Effect.BlindUp(elem, {duration: 0.2});
				$('showsearch').innerHTML = '(show filters)';
			}
		}

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
		<form action="index2.php" method="post" name="adminForm" id="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="2">List Courses</th>
		</tr>
		<tr>
			<td colspan="3" align="right" nowrap="nowrap">
			<a href="javascript:void(0)" onClick="tog('advsearch')"><span id="showsearch">(hide filters)</span></a>
			</td>
		</tr>
		</table>
		<div  id="advsearch" style="margin-bottom:10px;">
			<table cellspacing="0" cellpadding="2" border="0" width="100%">
				<tr>
					<td rowspan="20" width="80%" align="right" valign="top"><strong>Filter By:</strong>&nbsp;&nbsp;</td>
					<td nowrap="nowrap">Course Type:</td>
					<td><?php echo $lists['filter_type'];?></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Course Status:</td>
					<td><?php echo $lists['filter_status'];?></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Course Date:</td>
					<td>
							<input class="text_area" type="text" name="filter_course_start_date" id="filter_course_start_date" size="10" maxlength="10" value="<?php echo $lists['filter_course_start_date'] ? date("m/d/Y",strtotime($lists['filter_course_start_date'])): ''; ?>" />
							<input name="reset" type="reset" class="button" onclick="return showCalendar('filter_course_start_date', 'm/d/Y');" value="..." /><fonct class="small">mm/dd/yyyy format</font>
					</td>
				</tr>
				<tr>
					<td nowrap="nowrap">Course Active:</td>
					<td><?php echo $lists['filter_active'];?></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Course ID:</td>
					<td><input type="text" name="filter_course_id" id="filter_course_id" value="<?php echo $lists['filter_course_id']; ?>" size="20" maxlength="20" class="inputbox" /></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Instructor(s):</td>
					<td><?php echo $lists['filter_instructor'];?></td>
				</tr>
				<tr>
					<td nowrap="nowrap">Location:</td>
					<td><?php echo $lists['filter_location'];?></td>
				</tr>
				<tr>
					<td></td>
					<td nowrap="nowrap"><input type="submit" id="applybutton" value="Apply Filter(s)" style="margin-right:20px;" /><input type="submit" id="resetbutton" value="Reset Filter(s)" onClick="resetFilters();"/></td>
				</tr>
			</table>
		</div>

		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th class="title">Type</th>
			<th class="title">Status</th>
			<th class="title">Paid</th>
			<th class="title">Date/Time</th>
			<th width="5%" class="title" nowrap="true">Active</th>
			<th>ID</th>
			<th class="title">Instructor(s)</th>
			<th>Seats Taken/Capacity</th>
			<th class="title">Location</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($courses); $i < $n; $i++) {
			$course = $courses[$i];

			$link 	= 'index2.php?option=com_course&task=viewA&id='. $course->course_id;

			$img 	= $course->published ? 'tick.png' : 'publish_x.png';
			$alt 	= $course->published ? 'Published' : 'Unpublished';

			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo ($course->relatedIsDefined('Type') ? $course->Type->type_name : ''); ?>
				</td>
				<td>
				<?php echo ($course->relatedIsDefined('Status') ? $course->Status->status_name : ''); ?>
				</td>
				<td>
				<?php echo ($course->is_paid) ? '<img src="' . A25_Link::to('/includes/js/ThemeOffice/dollar.png').'" width="16" height="16" border="0" title="This course has been processed for payment."/>' : ''; ?>
				</td>
				<td>
				<?php
				if ( empty($link) || ( $course->checked_out && ( $course->checked_out != $my->id ) ) ) {
					echo $course->course_start_date;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="View Course">
					<?php echo $course->course_start_date ? $course->course_start_date : '<i>No Date / Time Assigned</i>'; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td align="center">
				<img src="images/<?php echo $img;?>" width="12" height="12" border="0" alt="<?php echo $alt; ?>" />
				</td>
				<td align="center">
					<?php echo $course->course_id; ?>
				</td>
				<td>
					<?php echo ($course->instructor_id > 0 ? $course->Instructor->name . ($course->instructor_2_id > 0  ? ', ' . $course->Instructor2->name : '') : '<i>No Instructor Assigned</i>'); ?>
				</td>
				<td align="center">
				<?php
					if (array_key_exists($course->course_id, $lists['enrolled'])) { echo $lists['enrolled'][$course->course_id]; } else { echo '0'; }
					echo '/' . $course->course_capacity;
				?>
				</td>
				<td>
				<?php echo $course->getLocationName(); ?>
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
}
?>
