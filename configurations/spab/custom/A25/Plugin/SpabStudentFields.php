<?php
class A25_Plugin_SpabStudentFields implements
    A25_ListenerI_LicenseNo, A25_ListenerI_AdminStudentForm,
    A25_ListenerI_AppendEnrollmentReportFormatRow, A25_ListenerI_LicenseInfo,
    A25_ListenerI_Doctrine,
    A25_ListenerI_Employer
{
    public function getStudentLicenseNumber(A25_Record_Student $student)
    {
        echo $student->cdl_no;
    }

    public function afterFiltersAdminListStudent($lists, $where, $database, $option)
    {
        $filter_cdl_no = $_GET['filter_cdl_no'];
        $filter_cdl_no = $database->getEscaped(
            trim(strtolower($filter_cdl_no))
        );
        $lists['filter_cdl_no'] = $filter_cdl_no;

        if ($filter_cdl_no) {
            $where[] = "s.`cdl_no` LIKE '%$filter_cdl_no%'";
        }

        return array( 'lists' => $lists, 'where' => $where);
    }

    public function studentFormAfterLicenseStatus(A25_Form_Record_Student $form)
    {
        $cdl_no = new A25_Form_Element_Text('cdl_no');
        $cdl_no->setRequired(true)
                ->setLabel('CDL Number');
        $form->addElement($cdl_no);
        
        $employer = new A25_Form_Element_Text('employer');
        $employer->setRequired(true)
                ->setLabel('Employer');
        $form->addElement($employer);
        
        $county = new A25_Form_Element_Text('employer_county');
        $county->setRequired(true)
                ->setLabel('County where employed');
        $form->addElement($county);
    }

    public function afterLicenseStateFilterAdminListStudentHtml($filter_cdl_no)
    {
        ?>
		<tr>
			<td style="text-align: right">CDL Number:</td>
			<td><input type="text" name="filter_cdl_no" id="filter_cdl_no" value="<?php echo $filter_cdl_no; ?>" size="20" maxlength="20" class="inputbox" /></td>
		</tr>
		<?php
    }

    public function afterLicenseIssuingStateRegister()
    {
        echo '
		<tr>
			<td class="formlabel"><label for="cdl_no">CDL Number:</label></td>
			<td><input type="text" name="cdl_no" id="cdl_no" size="30" maxlength="64" class="inputbox required" tmt:required="conditional" tmt:dependonradio="validdl1" tmt:errorclass="invalid" tmt:message="Please enter your CDL number." value="" /></td>
		</tr>';
    }
    
    public function afterZipCode() {
        echo '
		<tr>
			<td class="formlabel"><label for="employer">Employer:</label></td>
			<td><input type="text" name="employer" id="employer" size="30" maxlength="128" class="inputbox required" tmt:required="conditional" tmt:dependonradio="validdl1" tmt:errorclass="invalid" tmt:message="Please enter your employer." value="" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="employer_county">County where employed:</label></td>
			<td><input type="text" name="employer_county" id="employer_county" size="30" maxlength="64" class="inputbox required" tmt:required="conditional" tmt:dependonradio="validdl1" tmt:errorclass="invalid" tmt:message="Please enter the county where you are employed." value="" /></td>
		</tr>';
    }

    public function afterLicenseIssuingState(A25_Form_Record_Register $form)
    {
        $cdl_no = new A25_Form_Element_Text('cdl_no');
        $cdl_no->setLabel('CDL Number')
        ->setRequired(true);
        $form->addElement($cdl_no);
        
        $employer = new A25_Form_Element_Text('employer');
        $employer->setRequired(true)
                ->setLabel('Employer');
        $form->addElement($employer);
        
        $county = new A25_Form_Element_Text('employer_county');
        $county->setRequired(true)
                ->setLabel('County where employed');
        $form->addElement($county);
    }

    public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
    {
        if (!$doctrineRecord instanceof A25_Record_Student) {
            return;
        }

        $doctrineRecord->hasColumn('cdl_no', 'string', 64, array(
             'type' => 'string',
             'length' => 64,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));

        $doctrineRecord->hasColumn('employer', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));

        $doctrineRecord->hasColumn('employer_county', 'string', 64, array(
             'type' => 'string',
             'length' => 64,
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
        $formatRow['CDL #'] = $enroll->Student->cdl_no;
        return $formatRow;
    }
    
    public function capitalizeLicenseNumber(A25_Record_Student $student)
    {
        $student->cdl_no = strtoupper($student->cdl_no);
    }
    
    public function validateLicenseInfo(A25_Record_Student $student)
    {
        return true;
    }
}
