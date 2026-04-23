<?php

class A25_Plugin_LicenseIssueDate implements
    A25_ListenerI_Doctrine, A25_ListenerI_LicenseInfo,
    A25_ListenerI_AdminStudentForm,
    A25_ListenerI_AppendEnrollmentReportFormatRow
{
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_Student) {
            $doctrineRecord->hasColumn('license_issue_date', 'date', null, array(
                'type' => 'date',
                'fixed' => false,
                'unsigned' => false,
                'primary' => false,
                'notnull' => false,
                'autoincrement' => false
                ));
        }
	}
    
    public function studentFormAfterLicenseStatus(A25_Form_Record_Student $form)
    {
        $issue_date = new A25_Form_Element_Date('license_issue_date');
        $issue_date->setRequired(false)
                ->setLabel('License Issue Date');
        $form->addElement($issue_date);
    }
  
    public function appendEnrollmentReportFormatRow(array $formatRow,
        A25_Record_Enroll $enroll)
    {
        $formatRow['License Issue Date'] = $enroll->Student->license_issue_date;
        return $formatRow;
    }

    public function afterLicenseIssuingStateRegister()
    {
        echo '
		<tr>
			<td class="formlabel"><label for="license_issue_date">Permit/License Issue Date (ISS):</label></td>
			<td><input type="text" name="license_issue_date" id="license_issue_date" size="15" maxlength="10" class="inputbox required" tmt:required="conditional"  tmt:dependonradio="validdl1" tmt:datepattern="M/D/YYYY" tmt:errorclass="invalid" tmt:message="Please enter your license/permit issue date in mm/dd/yyyy format." value="" /> <span class="small">(mm/dd/yyyy format)</span></td>
		</tr>';
    }

    public function afterLicenseIssuingState(A25_Form_Record_Register $form)
    {
        $issue_date = new A25_Form_Element_Text('license_issue_date');
        $issue_date->setLabel('License Issue Date')
        ->setRequired(true);
        $form->addElement($issue_date);
    }
    
    public function validateLicenseInfo(A25_Record_Student $student)
    {
        if ((int) $student->license_status == A25_Record_Student::licenseStatus_unlicensed) {
            return true;
        }
        if (!$student->license_issue_date) {
            $student->_error = "License Issue Date cannot be empty.";
            return false;
        }
        // For some reason this doesn't work if done as 1 line
        $student->license_issue_date = strtotime($student->license_issue_date);
        $student->license_issue_date = date('Y-m-d', $student->license_issue_date);
        return true;
    }
}
