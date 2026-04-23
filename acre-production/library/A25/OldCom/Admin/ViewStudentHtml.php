<?php

class A25_OldCom_Admin_ViewStudentHtml 
{
	public static function viewStudent(A25_Record_Student $student, $optionForReturnButton )
	{
        global $my;
		A25_DataHtmlFunctions::html_css_valignTop();
		mosCommonHTML::loadCalendar();
		$editLink = "<small>(<a href='index2.php?option=com_student&task=studentForm&id=" . $student->student_id . "'>edit</a>)</small>";
		// HACK: There are some hacks in here to give court admins access to view students.
		
    A25_FirefoxPrintWarning::run();
    
		self::javascript_submitButton();
		self::html_common_adminViewStudentHeader();

		self::html_common_adminFormHeader(60);
		self::html_common_sectionLabel($my,$editLink,'Student Details');
		echo '<tr><td colspan=2>';
		A25_FormLoader::run('Student',
				'option=com_student&task=viewA&id='.$id,true);
		echo '</tr></td>';
		self::html_common_studentAccountBalance($student);
		A25_DataHtmlFunctions::html_common_blankTableRow(2);
		self::html_common_enrollment($student);
		self::html_common_notes($student);
		self::html_common_adminFormFooter();

		A25_DataHtmlFunctions::html_common_blankTableColumn();
			
		// HACK to remove the control panel if user is a court admin
		if(!$my->isCourtAdministrator()) {
			self::html_common_adminFormHeader(40);
			self::html_common_studentActions($student);
			self::html_common_adminFormFooter();
		}
		self::html_common_adminViewStudentFooter();
		self::html_common_formHiddenInputs($optionForReturnButton,$student);
	}
	private static function javascript_submitButton()
	{
		?><script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		  var form = document.adminForm;
		  if (pressbutton == 'list') {
		    submitform( pressbutton );
		    return;
		  }
		}
		//-->
		</script><?php
	}
	private static function html_common_adminViewStudentHeader()
	{
        echo A25_HtmlGenerationFunctions::tableWithOnlyHeading('View Student', 'class="adminheading"');
		?><table width="100%">
		<tr><?php
    }
	private static function html_common_sectionLabel($my,$editLink,$label)
	{
		$innerHtml = $label . ' ';
		$innerHtml .= (!$my->isCourtAdministrator()) ? $editLink : '';
		echo A25_HtmlGenerationFunctions::singleColumnHeader($innerHtml, 'colspan="2"');
    }
	private static function html_common_studentAccountBalance(A25_Record_Student $studentRecord)
	{
		$account_balance = $studentRecord->getAccountBalance();
		if ($account_balance < 0) {
			$temp = '<div style="color:red">($'. number_format($account_balance,2).')</div>';
		} else {
			$temp = '<div>$'. number_format($account_balance,2) . '</div>';
		}
		$temp .= 'A positive number means the student owes the listed amount. A negative number means there is a credit equal to the listed amount.';
					
		
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Account Balance',$temp));
    }
	private static function html_common_enrollment(A25_Record_Student $studentRecord)
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader('Enrollments','colspan="2"');
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				'Click on a course date/time to update enrollment information for this student.','colspan="2"');

		$view = new A25_View_StudentEnrollments($studentRecord);
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				$view->run(), 'colspan="2"');
    }
	private static function html_common_notes(A25_Record_Student $studentRecord)
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader('Notes','colspan="2"');
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				$studentRecord->showNotes(), 'colspan="2"');
    }
	private static function html_common_formHiddenInputs($option, A25_Record_Student $studentRecord)
	{
		$innerHtml = '<input type="hidden" name="option" value="' . $option . '" />
			<input type="hidden" name="student_id" value="' . $studentRecord->student_id . '" />
			<input type="hidden" name="task" value="" />';
		echo A25_HtmlGenerationFunctions::adminForm($innerHtml);
    }
	private static function html_common_adminViewStudentFooter()
	{
		?></tr>
		</table><?php
    }
	private static function html_common_adminFormHeader($width)
	{
        echo A25_HtmlGenerationFunctions::adminFormHeader($width);
    }
	private static function html_common_adminFormFooter()
	{
        echo A25_HtmlGenerationFunctions::adminFormFooter();
	}
	private static function html_common_studentActions($row)
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader('Student Actions', 'colspan="2"');
		?><tr>
					<td colspan="2">
					<div id="cpanel">

	<?php // Hidden dependency on $studentRecord being a A25_Record_Student
	include(dirname(__FILE__) .
			'/../../../../administrator/components/com_student/student.cpanel.php'); ?>
					</div>
					</td>
				</tr><?php
    }
}
?>
