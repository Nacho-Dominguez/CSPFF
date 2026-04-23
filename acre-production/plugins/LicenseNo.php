<?php

use Acre\A25\Printing\CertInterface;

class A25_Plugin_LicenseNo implements
    A25_ListenerI_LicenseNo, A25_ListenerI_AdminStudentForm,
    A25_ListenerI_AppendEnrollmentReportFormatRow, A25_ListenerI_LicenseInfo,
    A25_ListenerI_Doctrine,
    A25_ListenerI_CertPdf
{
    public function getStudentLicenseNumber(A25_Record_Student $student)
    {
        echo $student->license_no;
    }

    public function afterCompletionDate(
        CertInterface $certPdf,
        A25_Record_Enroll $enroll
    ) {
        $student = $enroll->Student;
        $certPdf->writeToLicenseNumberLines($student->license_no);
    }

    public function afterFiltersAdminListStudent($lists, $where, $database, $option)
    {
        $filter_license_no = $_GET['filter_license_no'];
        $filter_license_no = $database->getEscaped(
            trim(strtolower($filter_license_no))
        );
        $lists['filter_license_no'] = $filter_license_no;

        if ($filter_license_no) {
            $where[] = "s.`license_no` LIKE '%$filter_license_no%'";
        }

        return array( 'lists' => $lists, 'where' => $where);
    }

    public function studentFormAfterLicenseStatus(A25_Form_Record_Student $form)
    {
        $license_no = new A25_Form_Element_Text('license_no');
        $license_no->setRequired(false)
                ->setLabel('License Number');
        $form->addElement($license_no);
    }

    public function afterLicenseStateFilterAdminListStudentHtml($filter_license_no)
    {
        ?>
		<tr>
			<td style="text-align: right">License Number:</td>
			<td><input type="text" name="filter_license_no" id="filter_license_no" value="<?php echo $filter_license_no; ?>" size="20" maxlength="20" class="inputbox" /></td>
		</tr>
		<?php
    }

    public function afterLicenseIssuingStateRegister()
    {
        echo '
		<tr>
			<td class="formlabel"><label for="license_no">Permit/License Number (DLN) (exclude dashes):</label></td>
			<td><input type="text" name="license_no" id="license_no" size="30" maxlength="80" class="inputbox required" tmt:required="conditional" tmt:dependonradio="validdl1" tmt:errorclass="invalid" tmt:message="Please enter your driver\'s license number." value="" /></td>
		</tr>';
        if (A25_DI::PlatformConfig()->confirmLicenseNo) {
            echo '
                <script type="text/javascript">
                    function confirmLicenseNo() {
                        var license_no = document.getElementById("license_no").value
                        var confirm_license_no = document.getElementById("confirm_license_no").value
                        if(license_no != confirm_license_no) {
                            alert(\'License numbers do not match\');
                            return false;
                        }
                    }
                </script>
                <tr>
                    <td class="formlabel"><label for="confirm_license_no">Confirm Permit/License Number (DLN) (exclude dashes):</label></td>
                    <td><input type="text" name="confirm_license_no" id="confirm_license_no" onblur="confirmLicenseNo()" size="30" maxlength="80" class="inputbox required" tmt:required="conditional" tmt:dependonradio="validdl1" tmt:errorclass="invalid" tmt:message="Please confirm your driver\'s license number." onpaste="return false;" value="" /></td>
                </tr>';
        }
    }

    public function afterLicenseIssuingState(A25_Form_Record_Register $form)
    {
        $license_no = new A25_Form_Element_Text('license_no');
        $license_no->setLabel('License Number')
        ->setRequired(true);
        $form->addElement($license_no);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Student) {
            return;
        }

        $doctrineRecord->hasColumn('license_no', 'string', 60, array(
             'type' => 'string',
             'length' => 60,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
    }
    public function appendEnrollmentReportFormatRow(
        array $formatRow,
        A25_Record_Enroll $enroll
    ) {
        $formatRow['License #'] = $enroll->Student->license_no;
        return $formatRow;
    }
    
    public function capitalizeLicenseNumber(A25_Record_Student $student)
    {
        $student->license_no = strtoupper($student->license_no);
    }
    
    public function validateLicenseInfo(A25_Record_Student $student)
    {
        if ((int) $student->license_status == A25_Record_Student::licenseStatus_unlicensed) {
            return true;
        }
        if (!$student->license_no) {
            $student->_error = "License number cannot be empty.";
            return false;
        }
        if (!ctype_alnum($student->license_no)) {
            $student->_error = "License number may only contain letters and numbers.";
            return false;
        }
		$finder = new A25_MosDbFinder('A25_Record_Student', A25_DI::DB());
        if ($student->license_no && $student->license_state == 'KY') {
            if (!preg_match("/^[a-zA-Z]\d{8}$/", $student->license_no)) {
                $student->_error = "License number must be in format X12345678.";
                return false;
            }
        }
		$students = $finder->loadRecordsWithForeignKey('license_no', $student->license_no);
		if (count($students) > 0) {
            foreach ($students as $s) {
                if ($student->student_id == $s->student_id) {
                    return true;
                }
            }
            $student->_error = 'This license/permit is already associated with'
                    . ' an account. <a href="' . PlatformConfig::contactUrl() . '">Contact us</a> if you need help logging into your account.';
			return false;
        }
        return true;
    }
}
