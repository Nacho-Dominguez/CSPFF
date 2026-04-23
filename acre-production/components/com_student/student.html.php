<?php
/**
 * $URL$
 * 
 * @package aliveat25_components
 * @subpackage student
 * @author Christiaan van Woudenberg
 * @version $LastChangedRevision$, $Date$
 * @since Revision 1, 2007-11-22
 */

/**
 * Contains the functions to generate the GUI of com_student.
 * 
 * @package aliveat25_components
 * @subpackage student
 * @author Christiaan van Woudenberg
 * @since Revision 1, 2007-11-22
 * @static
 */
class HTML_student {
	/**
	 * Prints the login form for a new student, where they pick a user Id and 
	 * enter a date of birth.  This is a helper function, meant to be called 
	 * from loginForm().
	 *
	 * @param integer $course_id
	 * @param string $nexttask
	 * @return void
	 * @author Thomas Albright
	 * @version NEW
	 * @since NEW
	 */
	function printRegisterLoginForm ($course_id, $nexttask) {
		global $mosConfig_live_site, $Itemid;
		?>
<h2 style="margin-top: 40px; font-size: 24px;">New Student Registration</h2>
		<?php echo PlatformConfig::loginEnrollDuplicateAccountWarningText() ?>
		<form method="post" name="register" id="register" action="<?php echo A25_Link::https('/index.php?option=com_student&task=registerForm&Itemid=' . $Itemid); ?>" tmt:validate="true">
		<input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
		<input type="hidden" name="nexttask" value="<?php echo $nexttask; ?>" />
		<table width="100%" border="0">
		<tr>
			<td colspan="2">
				Username may be any combination of letters, numbers, and "_".  It is not case-sensitive.
			</td>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif" border="0" width="10" height="8" align="absmiddle" /> Required Field
			</td>
		</tr>
		<tr>
			<td class="formlabel"><label for="userid">Choose a Username:</label></td>
			<td><input type="text" name="userid" id="userid" size="30" maxlength="50" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your User ID." value="" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="date_of_birth">Student&#8217;s Date of Birth:</label></td>
			<td><input type="text" name="date_of_birth" id="date_of_birth" size="15" maxlength="10" class="inputbox required" tmt:required="true" tmt:datepattern="M/D/YYYY" tmt:errorclass="invalid" tmt:message="Please enter your date of birth in mm/dd/yyyy format." value="" /> <span class="small">(mm/dd/yyyy format)</span></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Continue ..." /></td>
		</tr>
		</table>
		</form>
		<?php
	}

	/**
	 * Show payment form
	 * @author Christiaan van Woudenberg
	 * @version July 6, 2006
	 *
	 * @param object $course
	 * @param object $student
	 * @param array $lists
	 * @param integer $course_id
	 * @param string $nexttask
	 * @return void
	 */
	function paymentForm( $course, $student, $lists, $email, $course_id, $nexttask ) {
		global $mainframe, $mosConfig_live_site, $Itemid;
		?>
		<table width="100%" border="0">
		<tr>
			<td width="150">
			</td>
			<td>
			<img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif" border="0" width="10" height="8" align="absmiddle" /> Required Field
			</td>
		</tr>
		<tr>
			<td class="formlabel"><label for="payment_id">Payment Type:</label></td>
			<td><?php echo $lists['payment_id']; ?> <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="Register" /></td>
		</tr>
		</table>
		<?php
	}
}
?>
