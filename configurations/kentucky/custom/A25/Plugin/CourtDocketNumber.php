<?php

class A25_Plugin_CourtDocketNumber implements A25_ListenerI_AdminEnroll,
    A25_ListenerI_EditCourt, A25_ListenerI_AppendEnrollmentReportFormatRow,
    A25_ListenerI_AddEnrollCheck, A25_ListenerI_MakeEnrollment,
    A25_ListenerI_StudentConfirmation, A25_ListenerI_Doctrine
{
	public function afterEnrollmentDate(A25_Record_Enroll $enroll)
	{
		?>
      <tr>
          <td align="left"><?php echo A25_DI::PlatformConfig()->courtDocketNumberName?>:</td>
          <td align="left"><?php echo $enroll->court_docket_number; ?></td>
      </tr>
		<?php
	}
  
  public function afterIsLateEdit(A25_Form_Record_Enroll $form)
  {
    $docket = new A25_Form_Element_Text('court_docket_number');
    $docket->setRequired(false);
    $docket->setLabel(A25_DI::PlatformConfig()->courtDocketNumberName);
		$form->addElement($docket);
  }
  
  public function afterIsLateNew()
  {
		?>
		<tr>
			<td>
			<?php echo A25_DI::PlatformConfig()->courtDocketNumberName?>
			</td>
			<td>
				<input type="text" name="court_docket_number" />
			</td>
		</tr>
		<?php
  }

  public function duringEditCourtForm($row)
  {
		$court = A25_Record_Court::retrieve($row->court_id);
		$yes = '';
		$no = '';
		if ($court && $this->requiresDocketNumber($court)) {
			$yes = 'checked=checked';
		}
		else {
			$no = 'checked=checked';
		}
		?>
<tr>
	<td>Collect <?php echo A25_DI::PlatformConfig()->courtDocketNumberName?>:</td>
	<td>
		<input type="radio" name="collect_docket_number" id="collect_docket_number0" value="0" <?php echo $no; ?>/>
		<label for="collect_docket_number0">No</label>
		<input type="radio" name="collect_docket_number" id="collect_docket_number1" value="1" <?php echo $yes; ?>/>
		<label for="collect_docket_number1">Yes</label>
	</td>
</tr>
		<?php
  }
  
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_Court)
		{
      $doctrineRecord->hasColumn('collect_docket_number', 'integer', 1, array(
           'type' => 'integer',
           'length' => 1,
           'unsigned' => 1
           ));
		}
    if ($doctrineRecord instanceof A25_Record_Enroll)
    {
      $doctrineRecord->hasColumn('court_docket_number', 'string', 40, array(
           'type' => 'string',
           'length' => 40,
           'unsigned' => 1,
           'notnull' => true,
           'default' => '0'
           ));
    }
	}
  
  public function addEnrollCheck(A25_Record_Enroll $enroll)
  {
    $error = false;
    if ($enroll->court_id > 0 && $this->requiresDocketNumber($enroll->Court)
        && $enroll->court_docket_number == '' ) {
      $enroll->_error = "Please enter your " . A25_DI::PlatformConfig()->courtDocketNumberName . ".";
      $error = true;
    }
    return $error;
  }
  
  public function afterEnrollInCourse(A25_Record_Enroll $enroll)
  {
		if ( $enroll->court_id > 0 && $this->requiresDocketNumber($enroll->Court)
				&& $_REQUEST['court_docket_number'] == '' )
			throw new A25_Exception_InvalidEntry(
					'You must enter your ' . A25_DI::PlatformConfig()->courtDocketNumberName);
		
		$enroll->court_docket_number = $_REQUEST['court_docket_number'];
  }
  
  public function afterCourtList()
  {
		?>
		<div class="row" id="docketNumberForm" style="display: none; margin-top: 8px">
      <div class="col-sm-4"><label for="court_docket_number"><?php echo A25_DI::PlatformConfig()->courtDocketNumberName?>:
        <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif"
          border="0" width="10" height="8" align="absmiddle" /></label>
      </div>
      <div class="col-sm-8">
        <input type="text" id="court_docket_number" name="court_docket_number" />
      </div>
		</div>
		<?php
  }
	public function duringJavascript()
	{
		$courts = Doctrine::getTable('A25_Record_Court')->findBy('collect_docket_number', 1);
		$conditional = 'false';

		if (count($courts) > 0) {
			$conditional = '$F(elem)==' . $courts[0]->court_id;
			for ($i=1; $i<count($courts); $i++) {
				$conditional .= ' || $F(elem)=='
						. $courts[$i]->court_id;
			}
		}

		?>
		function checkCourt(elem) {
			if (<?php echo $conditional; ?>) {
				if ($('docketNumberForm').style.display == 'none') {
					new Effect.BlindDown('docketNumberForm', {duration: 0.2});
				}
				$('court_docket_number').options[0].value = 'required';
		    } else {
				$('docketNumberForm').style.display = 'none';
				$('court_docket_number').options[0].value = '';
			}
		}
		<?php
	}
  
  public function appendEnrollmentReportFormatRow(array $formatRow,
      A25_Record_Enroll $enroll)
  {
		$formatRow[A25_DI::PlatformConfig()->courtDocketNumberName] = $enroll->court_docket_number;
    return $formatRow;
  }
  
	private function requiresDocketNumber(A25_Record_Court $court)
	{
		return $court->collect_docket_number;
	}
}
