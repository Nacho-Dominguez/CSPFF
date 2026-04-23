<?php

class A25_OldCom_Admin_EditCourtHtml
{
    public static function editCourt( &$row, $lists, $option, $locs ) {
		A25_Javascript::loadOverlib();

		self::javascript_function();
		self::html_common_formHeader();
		self::html_common_adminEditCourtHeading($row);
		
		if (array_key_exists($row->parent,$locs) || $locs[0] == 'all') {
			self::html_common_adminCourtHeader();
		}

		self::html_common_adminFormHeader();
		self::html_common_details();
		A25_DataHtmlFunctions::html_common_requiredField();
		self::html_common_courtId($row);
		self::html_common_locationParent($lists);
		self::html_common_inputCourtName($row);
		self::html_common_inputAddress1($row);
		self::html_common_inputAddress2($row);
		self::html_common_inputCity($row);
		self::html_common_selectState($lists);
		self::html_common_inputZip($row);
		self::html_common_inputPhone($row);
		self::html_common_inputRegistrationFee($row);
		self::html_common_inputSurchargeFee($row);

		self::fireAfterSurchargeFee($row);

		self::html_common_published($lists);
		A25_DataHtmlFunctions::html_common_blankTableRow(3);
		self::html_common_adminFormFooter();

		if ($locs[0] == 'all' || array_key_exists($row->parent,$locs)) {
			self::html_common_adminCourtMiddle();
			self::html_common_adminFormHeader();
			self::html_common_courtAdministrator();
			self::html_common_availableAndCurrentCourtAdministrators($lists);
			self::html_common_adminFormFooter();
			self:: html_common_adminCourtFooter();
		} else {
			self::html_common_forHiddenInputParent($row);
		}

		self::html_common_formHiddenInputs($option,$row);
		self::html_common_formFooter();
	}
	private static function javascript_function()
	{
		?><script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}
			// do field validation
			if (form.court_name.value == "") {
				alert( "You must provide a court name." );
			} else if (!getSelectedValue('adminForm','state')) {
				alert( "Please choose a state." );
			} else {
				turnon('currAdmins');
				submitform( pressbutton );
			}
		}
		//-->
		</script><?php
	}
	private static function html_common_adminEditCourtHeading($row)
	{
		$html = 'Court:
			<small>'.($row->court_id ? 'Edit' : 'New') .'</small>';
		echo A25_HtmlGenerationFunctions::tableWithOnlyHeading($html, 'class="adminheading"');
	}
	private static function html_common_formHeader()
	{
		?><form action="index2.php" method="post" name="adminForm"><?php
	}
	private static function html_common_formHiddenInputs($option,$row)
	{
		?><input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="court_id" value="<?php echo $row->court_id; ?>" />
		<input type="hidden" name="xref_id" value="<?php echo (mosGetParam( $_GET, 'xref_id' )) ? mosGetParam( $_GET, 'xref_id' ) : ''; ?>" />
		<input type="hidden" name="created" value="<?php echo $row->created; ?>" />
		<input type="hidden" name="created_by" value="<?php echo $row->created_by; ?>" />
		<input type="hidden" name="task" value="" /><?php
	}
	private static function html_common_formFooter()
	{
		?></form><?php
	}
	private static function html_common_forHiddenInputParent($row)
	{
		?><input type="hidden" name="parent" value="<?php echo $row->parent; ?>" /><?php
	}
	private static function html_common_adminFormHeader()
	{
		?><table class="adminform"><?php
	}
	private static function html_common_adminFormFooter()
	{
		?></table><?php
	}
	private static function html_common_details()
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader('Details', 'colspan="2"');
	}
	private static function html_common_courtId($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Court ID:',$row->court_id ? $row->court_id : '<i>Automatically assigned after save.</i>'));
	}
	private static function html_common_locationParent($lists)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Location Parent: '.A25_DataHtmlFunctions::html_common_requiredFieldMark(),
					$lists['parent']));
	}
	private static function html_common_inputCourtName($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Court Name: '.A25_DataHtmlFunctions::html_common_requiredFieldMark(),
				'<input type="text" name="court_name" size="30" maxlength="80" class="inputbox" value="'.$row->court_name.'" />'));
	}
	private static function html_common_inputAddress1($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Address 1:','<input type="text" name="address_1" size="30" maxlength="255" class="inputbox" value="'.$row->address_1.'" />'));
	}
	private static function html_common_inputAddress2($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Address 2:','<input type="text" name="address_2" size="30" maxlength="255" class="inputbox" value="'.$row->address_2.'" />'));
	}
	private static function html_common_inputCity($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('City:','<input type="text" name="city" size="30" maxlength="80" class="inputbox" value="'.$row->city.'" />'));
	}
	private static function html_common_selectState($lists)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('State: '.A25_DataHtmlFunctions::html_common_requiredFieldMark(),
				$lists['state']),
			array('','align="left"'));
	}
	private static function html_common_inputZip($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Zip:','<input type="text" name="zip" size="10" maxlength="10" class="inputbox" value="'.$row->zip.'" />'));
	}
	private static function html_common_inputPhone($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Phone:','<input type="text" name="phone" size="20" maxlength="30" class="inputbox" value="'.$row->phone.'" onBlur="fixPhone(this)" />'));
	}
	private static function html_common_inputRegistrationFee($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Registration Fee:','$<input type="text" name="fee" size="10" maxlength="10" class="inputbox" value="'. $row->fee .'" /> '.mosToolTip('Leave empty to use the default court fee of $' . PlatformConfig::defaultCourtFee . ' (recommended)', 'Registration Fee')));
	}
	private static function html_common_inputSurchargeFee($row)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Surcharge Fee:','$<input type="text" name="surcharge_fee" size="10" maxlength="10" class="inputbox" value="'. $row->surcharge_fee .'" /> '.mosToolTip('Please enter the dollar amount for the DOR fee assigned by this court.', 'Surcharge Fee')));
	}
	private static function html_common_published($lists)
	{
		echo A25_HtmlGenerationFunctions::arrayToRow(
			array('Published:',$lists['published']));
	}
	private static function html_common_courtAdministrator()
	{
		echo A25_HtmlGenerationFunctions::singleColumnHeader(
				'Court Administrators','colspan="2"');
		echo A25_HtmlGenerationFunctions::singleColumnRow(
				'The following users have permissions to manage this court and may view student enrollments referred by this court.
						<div class="required">You must choose Save or Apply above for these changes to take effect!</div>',
				'colspan="2"');
	}
	private static function html_common_availableAndCurrentCourtAdministrators($lists)
	{
		$availableCourtAdministrators = '<strong>Available Court Administrators</strong><br />'
										. $lists['availAdmins'].'<br />
										<input type="button" value="Add --&gt;" onclick="moveOptions($(\'availAdmins\'), $(\'currAdmins\'));" />';
		$currentCourtAdministrators = '<strong>Current Court Administrators</strong><br />'
										. $lists['currAdmins'].'<br />
										<input type="button" value="&lt;-- Remove" onclick="moveOptions($(\'currAdmins\'), $(\'availAdmins\'));" />';
		echo A25_HtmlGenerationFunctions::arrayToRow(
				array($availableCourtAdministrators,$currentCourtAdministrators),
				array('style="text-align:right; padding:0px 10px 20px 0px;" width="50%"','style="padding:0px 0px 20px 10px;" width="50%"'));
	}
	private static function html_common_adminCourtHeader()
	{
		?><table width="100%">
		<tr>
			<td valign="top" width="40%"><?php
	}
	private static function html_common_adminCourtMiddle()
	{
		?></td><?php
		echo A25_DataHtmlFunctions::html_common_blankTableColumn();
		?><td valign="top" width="60%"><?php
	}
	private static function html_common_adminCourtFooter()
	{
		?></td>
		</tr>
		</table><?php
	}

  private static function fireAfterSurchargeFee($row)
  {
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_EditCourt)
		  {
			  $listener->duringEditCourtForm($row);
		  }
	  }
  }
}
