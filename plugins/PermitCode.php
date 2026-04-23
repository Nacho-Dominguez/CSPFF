<?php

class A25_Plugin_PermitCode implements A25_ListenerI_AddEnrollCheck,
    A25_ListenerI_StudentConfirmation, A25_ListenerI_Doctrine,
    A25_ListenerI_AdminStudentForm, A25_ListenerI_MakeEnrollment,
    A25_ListenerI_AdminEnroll, A25_ListenerI_StudentConfirmationFields,
    A25_ListenerI_AppendEnrollmentReportFormatRow
{
    public function addEnrollCheck(A25_Record_Enroll $enroll)
    {
        $student = $enroll->Student;
        $error = false;
        if ($enroll->reason_id == A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit
                && !ctype_digit($student->pr_code)) {
            $enroll->_error = "Please enter a valid PR code. Do not include dashes or other non-numeric characters.";
            $error = true;
        }
        return $error;
    }
  
    public function afterEnrollInCourse(A25_Record_Enroll $enroll)
    {
        $student = $enroll->Student;
        if ( $enroll->reason_id == A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit
                && !ctype_digit($_REQUEST['pr_code']))
            throw new A25_Exception_InvalidEntry(
                    'Please enter a valid PR code. Do not include dashes or other non-numeric characters.');

        $student->pr_code = $_REQUEST['pr_code'];
    }
  
    public function afterCourtList()
    {
    }
    
    public function duringJavascript()
    {
        $conditional = '$F(elem)==' . A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit;

		?>
		function checkPRCode(elem) {
			if (<?php echo $conditional; ?>) {
				if ($('prCodeText').style.display == 'none') {
					new Effect.BlindDown('prCodeText', {duration: 0.2});
				}
				if ($('prCodeCheck').style.display == 'none') {
					new Effect.BlindDown('prCodeCheck', {duration: 0.2});
				}
		    } else {
				$('prCodeText').style.display = 'none';
				$('prCodeCheck').style.display = 'none';
				$('pr_code').value = '';
			}
		}
		<?php
    }
  
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_Student) {
            $doctrineRecord->hasColumn('pr_code', 'string', 32, array(
                'type' => 'string',
                'length' => 32,
                'fixed' => false,
                'unsigned' => false,
                'primary' => false,
                'default' => '',
                'notnull' => false,
                'autoincrement' => false
                ));
        }
	}
    
    public function studentFormAfterLicenseStatus(A25_Form_Record_Student $form)
    {
        $pr_code = new A25_Form_Element_Text('pr_code');
        $pr_code->setRequired(false)
                ->setLabel('PR Code (Confirmation Number)');
        $form->addElement($pr_code);
    }
	public function afterEnrollmentDate(A25_Record_Enroll $enroll)
	{
	}
    
    public function afterIsLateEdit(A25_Form_Record_Enroll $form)
    {
        echo 'WARNING: If this enrollment is for obtaining a driving permit, you must
            first enter the student\'s PR code on the edit student page.';
    }

    public function afterIsLateNew()
    {
		?>
		<tr>
			<td>
			PR Code (Confirmation Number):
			</td>
			<td>
				<input type="text" name="pr_code" />
			</td>
		</tr>
		<?php
    }
    
    public function afterReasonForEnrollment(A25_Record_Student $student,
      A25_Record_Course $course)
    {
		?>
        <div class="row" id="prCodeCheck" style="display: none; border: solid #0000ee 1px;
             padding: 12px; background-color: #eeeeee; margin: 12px 0px;">
            For students taking Alive at 25 to meet the Department of Revenue&#8217;s
            requirement to obtain a driver&#8217;s permit at the age of 15 1/2, you
            are required to pre-register with the Driver Testing and Education
            Division and utilize the unique PR code (Confirmation Number) that they issue to you.  Please select this
            link for <a href="https://mydmv.colorado.gov/_/" target="_blank">CO Department of Revenue</a>,
            select &quot;Apply for Drivers License/ID&quot;, complete their registration,
            and obtain your unique PR code.  Return to our registration system
            and input your PR code. Do not include dashes or other non-numeric
            characters. Also, please make sure you also get an appointment for
            the written test that will be after the class has been completed if
            you haven&#8217;t already done so. That is also on the link but under the
            Driver/RD Services tab.
        </div>
		<div class="row" id="prCodeText" style="display: none; margin-top: 8px">
            <div class="col-sm-4"><label for="pr_code">PR Code (Confirmation Number):
              <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif"
                border="0" width="10" height="8" align="absmiddle" /></label>
            </div>
            <div class="col-sm-8">
              <input type="text" id="pr_code" name="pr_code" value="<?php echo $student->pr_code?>"/>
            </div>
		</div>
		<?php
    }
  
    public function appendEnrollmentReportFormatRow(array $formatRow,
        A25_Record_Enroll $enroll)
    {
        $formatRow['PR Code'] = $enroll->Student->pr_code;
        return $formatRow;
    }
}
