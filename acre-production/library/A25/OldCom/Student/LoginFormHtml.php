<?php
require_once(dirname(__FILE__) . '/../../../../includes/sef.php');

class A25_OldCom_Student_LoginFormHtml
{
	public static function loginForm( $course, $course_id, $nexttask, $Itemid ) {
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
        ?>
		<div class="shell">
			<div id="colHeader"></div>
			<div id="colContent">
                Para inscribirse en el curso en l&iacute;nea de DDC, debe iniciar sesi&oacute;n. Si a&uacute;n no tiene una cuenta, reg&iacute;strese a continuaci&oacute;n.
				<h2 style="margin-top: 40px; font-size: 24px;">Iniciar sesi&oacute;n (Estudiante que regresa)</h2>
                    Nota: cada estudiante necesita su propia cuenta individual.
				<form method="post" name="logon" id="logon" action="<?php echo A25_Link::https('/index.php?option=com_student&Itemid=' . $Itemid); ?>" tmt:validate="true">
				<input type="hidden" name="action" value="login" />
				<input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
				<input type="hidden" name="nexttask" value="<?php echo $nexttask; ?>" />
				<table width="100%" border="0">
				<tr>
					<td width="150">
					</td>
					<td>
					<img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif');?>" border="0" width="10" height="8" align="absmiddle" /> Campo requerido
					</td>
				</tr>
				<tr>
					<td class="formlabel"><label for="email">Nombre de usuario:</label></td>
					<td><input type="text" name="email" id="email" size="30" maxlength="80" value="" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Por favor, introduzca su identificaci&oacute;n de usuario." /></td>
				</tr>
				<tr>
					<td class="formlabel"><label for="zip">Contrase&ntilde;a: (puede ser su c&oacute;digo postal)</label></td>
					<td><input type="password" name="zip" id="zip" size="10" maxlength="10" value="" class="inputbox required" tmt:required="true" tmt:pattern="positiveinteger" tmt:message="Ingrese su c&oacute;digo postal de 5 dígitos." /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Acceder" /></td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 18px; font-size: 14px;">
                        <i>Si ha olvidado su informaci&oacute;n de inicio de sesi&oacute;n, <a href=<?php echo PlatformConfig::contactUrl()?>>cont&aacute;ctenos.</a></i>
					</td>
				</tr>
				</table>
				</form>

				<?php if ($nexttask == "confirm") { ?>
                    <h2 style="margin-top: 40px; font-size: 24px;">Registrarse (Nuevo Estudiante)</h2>
                    <p class="required">Nota: Si alguna vez se ha registrado con nosotros, utilice el formulario de arriba para iniciar sesi&oacute;n en su cuenta existente.</p>
                    <form method="post" name="register" id="register" action="<?php echo A25_Link::https('/index.php?option=com_student&task=registerForm&Itemid=' . $Itemid); ?>" tmt:validate="true">
                    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
                    <input type="hidden" name="nexttask" value="<?php echo $nexttask; ?>" />
                    <table width="100%" border="0">
                    <tr>
                        <td colspan="2">
                            El nombre de usuario puede ser cualquier combinaci&oacute;n de letras, n&uacute;meros y "_". No es sensible a may&uacute;sculas y min&uacute;sculas.
                        </td>
                    </tr>
                    <tr>
                        <td width="150">
                        </td>
                        <td>
                        <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif" border="0" width="10" height="8" align="absmiddle" /> Campo requerido
                        </td>
                    </tr>
                    <tr>
                        <td class="formlabel"><label for="userid">Elige un nombre de usuario:</label></td>
                        <td><input type="text" name="userid" id="userid" size="30" maxlength="50" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Por favor, introduzca su identificaci&oacute;n de usuario." value="" /></td>
                    </tr>
                    <tr>
                        <td class="formlabel"><label for="date_of_birth">Fecha de nacimiento del alumno:</label></td>
                        <td><input type="text" name="date_of_birth" id="date_of_birth" size="15" maxlength="10" class="inputbox required" tmt:required="true" tmt:datepattern="M/D/YYYY" tmt:errorclass="invalid" tmt:message="Ingrese su fecha de nacimiento en formato mm/dd/aaaa." value="" /> <span class="small">(formato mm/dd/aaaa)</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Seguir ..." /></td>
                    </tr>
                    </table>
                    </form>
				<?php } ?>
			</div>
		</div>
        <?php
        }
        else {
		?>
		<div class="shell">
			<div id="colHeader"></div>
			<div id="colContent">
				<?php if ($course) {
					echo PlatformConfig::loginToEnrollText($course);
				} ?>
                <h2 style="margin-top: 40px; font-size: 24px;">Returning Student Login</h2>
				<?php echo PlatformConfig::loginEnrollMakeDuplicateAccountWarningText() ?>
				<form method="post" name="logon" id="logon" action="<?php echo A25_Link::https('/index.php?option=com_student&Itemid=' . $Itemid); ?>" tmt:validate="true">
				<input type="hidden" name="action" value="login" />
				<input type="hidden" name="course_id" value="<?php echo $course_id; ?>" />
				<input type="hidden" name="nexttask" value="<?php echo $nexttask; ?>" />
				<table width="100%" border="0">
				<tr>
					<td width="150">
					</td>
					<td>
					<img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif');?>" border="0" width="10" height="8" align="absmiddle" /> Required Field
					</td>
				</tr>
				<tr>
					<td class="formlabel"><label for="email">Username:</label></td>
					<td><input type="text" name="email" id="email" size="30" maxlength="80" value="" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your User ID." /></td>
				</tr>
				<tr>
					<td class="formlabel"><label for="zip">Password: (may be your zip code)</label></td>
					<td><input type="password" name="zip" id="zip" size="10" maxlength="10" value="" class="inputbox required" tmt:required="true" tmt:pattern="positiveinteger" tmt:message="Please enter your 5-digit zip code." /></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Log On" /></td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 18px; font-size: 14px;">
                        <i>Problems logging in? Try <a href="https://kb.iu.edu/d/ahic" target="_blank">Clearing your cookies</a>.</i>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 18px; font-size: 14px;">
						<i><?echo PlatformConfig::forgotLoginContactInfo()?></i>
					</td>
				</tr>
				</table>
				</form>

				<?php if ($nexttask == "confirm") {
					HTML_student::printRegisterLoginForm($course_id, $nexttask);
				} ?>
			</div>
		</div>
		<?php
        }
	}
}

?>
