<?php
/**
 * $URL$
 *
 * @package AliveAt25
 * @subpackage course
 */

/**
 * Contains the HTML-generating functions for com_course
 *
 * @package AliveAt25
 * @subpackage course
 * @author Christiaan van Woudenberg
 * @version $LastChangedRevision$, $Date$
 */
class ADMIN_HTML_course {

	function cancelForm( &$course, $lists, $option ) {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			if (pressbutton == 'cancelcourse') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ($F('subject') == '') {
				alert( "Please enter a subject for your message." );
			} else if ($F('message') == '') {
				alert( "Please enter a body for your message." );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th class="inbox">Cancel Course</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<form action="index2.php" method="post" name="adminForm" id="adminForm">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Message Details
					</th>
				</tr>
				<tr>
					<td colspan="2">
						<p>Add any additional information about the course cancellation below, and choose <strong>Send</strong> to cancel the course and send a message to all enrolled students.</p>
						<p>After canceling, please call each of the students who was registered for this class.</p>
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
					<td class="formlabel">
					Subject: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="subject" id="subject" size="30" maxlength="80" class="inputbox" style="width:400px;" value="<?php echo htmlentities(PlatformConfig::courseTitle, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?> Course Cancellation" />
					</td>
				</tr>
				<tr>
					<td class="formlabeltop">
					Message: <span class="required">&#149;</span>
					</td>
					<td>
					<textarea name="message" id="message" rows="15" style="width:400px;">The <?php echo htmlentities(PlatformConfig::courseTitle, ENT_COMPAT | ENT_HTML401, 'UTF-8') ?> course for which you were enrolled at <?php echo $course->Location->location_name; ?> on <?php echo $course->formattedDate('course_start_date'); ?> has been cancelled.</textarea>
					</td>
				</tr>
				</table>
				<input type="hidden" name="option" value="<?php echo $option; ?>" />
				<input type="hidden" name="course_id" value="<?php echo $course->course_id; ?>" />
				<input type="hidden" name="task" value="" />
				</form>
			</td>
			<td width="10">&nbsp;</td>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">Course Information</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php echo $course->showCourseInfo('full'); ?>
					</td>
				</tr>
				<tr>
					<th colspan="2">Enrolled Students</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php echo $course->showEnrollment(0); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php
	}

	/**
	 * Copied from mod_quickicon.php
	 *
	 * @param string $link
	 * @param string $image
	 * @param string $text
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

	/**
	 * Copied from mod_quickicon.php
	 *
	 * @param string $link
	 * @param string $image
	 * @param string $text
	 * @return void
	 */
	function quickiconButtonNewWindow( $link, $image, $text ) {
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>" target="_blank">
					<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}

}
?>
