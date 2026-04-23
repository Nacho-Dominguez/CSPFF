<?php

class A25_DataHtmlFunctions {
	public static function html_common_studentId(A25_Record_Student $student)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Student ID:',$student->student_id));
    }
	public static function html_common_userId(A25_Record_Student $student)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('User ID:',$student->userid));
    }
	public static function html_common_studentName(A25_Record_Student $student)
	{
		$name = $student->first_name . ' ' . $student->middle_initial . ' ' . $student->last_name;
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Name:',$name));
    }
	
	public static function html_common_studentAddress(A25_Record_Student $studentRecord)
	{
		$state = A25_Record_State::retrieve($studentRecord->state);

		$temp = $studentRecord->address_1;
		$temp .= ($studentRecord->address_2) ? '<br />' . $studentRecord->address_2 : '' ; 
		$temp .= ($studentRecord->city) ? '<br />' . $studentRecord->city : '' ;
		$temp .= ($state ? '<br />' . $state->state_name : '');
		$temp .= ($studentRecord->zip) ? '<br />' . $studentRecord->zip : '' ;
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Address:',$temp));
    }
	public static function html_css_valignTop()
	{
		echo ('<style type="text/css">
					td {
						vertical-align: top;
					}
			   </style>');
    }
	public static function html_common_studentEmail(A25_Record_Student $studentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('E-Mail:',$studentRecord->email));
    }
	public static function html_common_studentPrimaryPhone(A25_Record_Student $studentRecord)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Primary Phone:',$studentRecord->home_phone));
    }
	public static function html_common_studentSecondaryPhone(A25_Record_Student $studentRecord)
	{
		
		if ($studentRecord->work_phone) {
			echo A25_HtmlGenerationFunctions::arrayToRow(
				array('Secondary Phone:',$studentRecord->work_phone));
		}
    }
	public static function html_common_blankTableRow($colspan)
	{
		echo A25_HtmlGenerationFunctions::singleColumnRow('&nbsp;','colspan="'.$colspan.'"');
    }
    public static function html_common_blankTableColumn()
	{
        echo A25_HtmlGenerationFunctions::rowCell('&nbsp;', 'width="10"');
    }
	public static function html_common_requiredField()
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
            array('','<span class="required">&#149; Required Field</span>'),
			array('width="150"'));
	}
	public static function html_common_requiredFieldMark()
	{
		return '<span class="required">&#149;</span>';
	}

	public static function html_common_requiredFieldImage()
	{
		return '<img src="' . A25_Link::to('/includes/js/tmt_validator/images/required.gif').'" border="0" width="10" height="8" align="absmiddle" />';
	}
}
?>
