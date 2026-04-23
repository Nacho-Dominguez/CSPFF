<?php

class A25_OldCom_Admin_ViewCourseHtml
{
	public function viewCourse(A25_Record_Course $course, $option)
	{
		A25_Javascript::loadOverlib();
    A25_FirefoxPrintWarning::run();
		
		self::html_common_adminViewCourseHeading();

		self::html_common_adminViewCourseColumnHeader(60);

		self::html_common_tableAdminFormHeading();
		self::html_common_courseDetailsHeading($course);
		echo "<tr><td>";
		A25_FormLoader::run('Course',
				'option=com_course&task=viewA&id='.$id,true);
		echo '&nbsp;<br/>';

		if (PlatformConfig::allowInstructorsToSeeCourseRevenue
				|| !A25_DI::User()->isInstructor()) {
			self::html_common_courseRevenue($course);
		}

    $comments = $course->Comments->comments;
    if ($comments) {
      echo '<tr><td><strong>Comments left by instructor after course</strong></td></tr>'
      . '<tr><td>' . nl2br($comments) . '</td></tr>';
    }
    
		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		echo "</td></tr>";
		self::html_common_tableAdminFormFooter();

		self::html_common_formHeading();
		self::html_common_tableAdminFormHeading();
		self::html_common_courseEnrollment($course);
		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		self::html_common_tableAdminFormFooter();
		self::html_common_formHiddenInputs($option, $course);
		self::html_common_formFooter();
		self::html_common_adminViewCourseColumnFooter();

		A25_DataHtmlFunctions::html_common_blankTableColumn();

		self::html_common_adminViewCourseColumnHeader(40);
		self::html_common_tableAdminFormHeading();
		self::html_common_courseActions($course);
		self::html_common_tableAdminFormFooter();
		self::html_common_adminViewCourseColumnFooter();

		self::html_common_adminViewCourseFooter();
	}
	private static function html_common_courseRevenue(A25_Record_Course $course)
	{
		$revenue = '$'. number_format($course->getGrossRevenue(),2);
		echo "<dl class='zend_form' style='clear: both'>";
		echo '<dt><b>Gross Revenue</b></dt><dd>' . $revenue . '</dd>';
		echo '</dl>';
    }
	private static function html_common_courseEnrollment($course)
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader(
				'Course Enrollment','colspan="2"');
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				$course->showEnrollment(1),'colspan="2"');
	}
	private static function html_common_courseActions($row)
	{
		$path = dirname(__FILE__) . '/../../../../administrator/components/com_course/course.cpanel.php';
		echo A25_HtmlGenerationFunctions::singleColumnHeader(
				'Course Actions','colspan="2"');
		?><tr>
		<td colspan="2">
		<div id="cpanel">
		<?php include($path); ?>
		</div>
		</td>
		</tr><?php
	}
	private static function html_common_adminViewCourseHeading()
	{
		echo A25_HtmlGenerationFunctions::tableWithOnlyHeading(
				'View Course','class="adminheading"');
		?><table width="100%">
		<tr><?php
	}
	private static function html_common_courseDetailsHeading($row)
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader(
				'Course Details <small>(<a href="edit-course?id='
				.$row->course_id. '">edit</a>)</small>',
			'colspan="2"');
	}
	private static function html_common_formHeading()
	{
		?><form action="index2.php" method="post" name="adminForm" id="adminForm"><?php
	}
	private static function html_common_tableAdminFormHeading()
	{
		?><table class="adminform"><?php
	}
	private static function html_common_tableAdminFormFooter()
	{
		?></table><?php
	}
	private static function html_common_formFooter()
	{
		?></form><?php
	}
	private static function html_common_formHiddenInputs($option, $row)
	{
		?><input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="course_id" value="<?php echo $row->course_id; ?>" />
		<input type="hidden" name="location_id" value="<?php echo $row->location_id; ?>" />
		<input type="hidden" name="task" value="applyenroll" /><?php
	}
	private static function html_common_adminViewCourseColumnHeader($width)
	{
		?><td valign="top" width="<?php echo $width; ?>%"><?php
	}
	private static function html_common_adminViewCourseColumnFooter()
	{
		?></td><?php
	}
	private static function html_common_adminViewCourseFooter()
	{
		?></tr>
		</table><?php
	}
}
?>
