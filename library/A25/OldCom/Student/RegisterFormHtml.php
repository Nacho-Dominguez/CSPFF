<?php

class A25_OldCom_Student_RegisterFormHtml
{
    public static function registerForm(
        $course,
        $student,
        $lists,
    $userid,
        $dob,
        $nexttask,
        $Itemid
    ) {
    // Overlib is used by the SmsMessages plugin, at the very least.
        A25_Javascript::loadOverlib();
        echo '<div class="shell">';
        echo '<div id="colHeader">';
        echo '</div>';
        echo '<div id="colContent">';
        ?>
		<script language="javascript" type="text/javascript">
		// Makes the license info form visible if student isn't unlicensed.
		function checkDL(elem) {
			var ls = $F(elem);
			if ($F(elem) != <?php echo A25_Record_LicenseStatus::statusId_unlicensed; ?>) {
				if ($('yesDLForm').style.display == 'none') {
					new Effect.BlindDown('yesDLForm', {duration: 0.2});
				}
				$('license_state').options[0].value = 'required';
			} else {
				$('yesDLForm').style.display = 'none';
				$('license_state').options[0].value = '';
			}
		}

		// This will be triggered by the tmt:dependonradio attribute
		tmt_globalRules.dependonradio = function(fieldNode) {
			var isRequired = ($F('license_status5') != 5);
			if(isRequired && ($F(fieldNode) == '' || $F(fieldNode) == 0)) {
				return false;
			}
			return true;
		}

        // This will be triggered by the tmt:dependonselect attribute
		/*tmt_globalRules.dependonreason = function(fieldNode) {
            var isRequired = (document.register.reason_id.selectedIndex == 5);
            if(isRequired && $F(fieldNode) == '') {
                return false;
            }
            return true;
		}*/
        function confirmEmail() {
            var email = document.getElementById("email").value
            var confemail = document.getElementById("confemail").value
            if(email != confemail) {
                alert('Email Not Matching!');
            }
        }
		</script>
		<h2><?php echo _CREATE_A_NEW_ACCOUNT; ?></h2>
        <?php echo A25_DI::PlatformConfig()->createAccountComments;?>
        <form method="post" name="register" id="register" action="<?php echo A25_Link::to('/index.php?option=com_student&Itemid=' . $Itemid); ?>" tmt:validate="true" <?php if (A25_DI::PlatformConfig()->confirmLicenseNo) {echo 'onsubmit="return confirmLicenseNo();"';}?>>
		<input type="hidden" name="action" value="register" />
		<input type="hidden" name="course_id" value="<?php echo @$course->course_id ? $course->course_id : ''; ?>" />
		<input type="hidden" name="nexttask" value="<?php echo $nexttask; ?>" />
		<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
		<input type="hidden" name="date_of_birth" value="<?php echo $dob; ?>" />
		<table width="100%" cellpadding="0" cellspacing="6" border="0">
		<tr>
            <td>
			</td>
			<td>
			<img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /> <?php echo _INDICATES_REQUIRED_FIELD; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><h3><?php echo _NAME_AND_ADDRESS; ?></h3></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="first_name"><?php echo _FIRST_NAME . ' (' . _AS_APPEARS_ON_YOUR_LICENSE . ')'; ?>:</label></td>
			<td><input type="text" name="first_name" id="first_name" size="30" maxlength="80" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your first name." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="middle_initial"><?php echo _MIDDLE_INITIAL; ?>:</label></td>
			<td><input type="text" name="middle_initial" id="middle_initial" size="1" maxlength="1" class="inputbox" value="" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="last_name"><?php echo _LAST_NAME . ' (' . _AS_APPEARS_ON_YOUR_LICENSE . ')'; ?>:</label></td>
			<td><input type="text" name="last_name" id="last_name" size="30" maxlength="80" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your last name." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="address_1"><?php echo _MAILING_ADDRESS; ?>:</label></td>
			<td><input type="text" name="address_1" id="address_1" size="30" maxlength="80" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your street address." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="address_2"><?php echo htmlentities('Unit/Apt #');?>:</label></td>
			<td><input type="text" name="address_2" id="address_2" size="30" maxlength="80" class="inputbox" value="" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="city"><?php echo _CITY; ?>:</label></td>
			<td><input type="text" name="city" id="city" size="30" maxlength="80" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your city." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="state"><?php echo _STATE; ?>:</label></td>
			<td><?php echo $lists['state']; ?><img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="zip"><?php echo _ZIP_CODE; ?>:</label></td>
			<td><input type="text" name="zip" id="zip" size="10" maxlength="10" class="inputbox required" tmt:required="true" tmt:errorclass="invalid" tmt:message="Please enter your zip code." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
        </tr><?php self::fireAfterZipCode(); ?>
		<tr>
			<td colspan="2"><h3><?php echo _CONTACT_INFORMATION; ?></h3></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="email"><?php echo _EMAIL_ADDRESS; ?>:</label></td>
      <td><input type="email" name="email" id="email" size="30" maxlength="80" class="inputbox<?php
        if (A25_DI::PlatformConfig()->requireEmail) {
            echo ' required" tmt:required="true';
        }
            ?>" tmt:errorclass="invalid" tmt:message="Please enter your e-mail address." value="" /> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr>
			<td class="formlabel"><label for="confemail">Confirm <?php echo _EMAIL_ADDRESS; ?>:</label></td>
      <td><input type="email" name="confemail" id="confemail" size="30" maxlength="80" class="inputbox<?php
        if (A25_DI::PlatformConfig()->requireEmail) {
            echo ' required" tmt:required="true';
        }
            ?>" tmt:errorclass="invalid" tmt:message="Please confirm your e-mail address." value="" onblur="confirmEmail()" onpaste="return false;"/> <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr>
		<tr style="font-style: italic">
            <td style="padding-bottom: 12px"><?php echo _TO_ENSURE_YOU . A25_DI::PlatformConfig()->sendFromEmail . _TO_YOUR_CONTACTS; ?>.</td>
		</tr>
		<tr valign="top">
			<td class="formlabel"><label for="home_phone"><?php echo _PRIMARY_PHONE; ?>:</label></td>
			<td>
        <div style="display: inline-block; vertical-align: top; padding-right: 4px; padding-top: 2px;">
          <input type="text" name="home_phone" id="home_phone" size="20"
             maxlength="20" class="inputbox required" onBlur="fixPhone(this)"
             tmt:required="true" tmt:errorclass="invalid"
             tmt:message="Please enter your home phone number." value="" />
        </div>
        <?php self::fireRegistrationFormAfterEachPhoneNumber("home");?>
                <img src="<?php echo A25_Link::to('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" />
      </td>
		</tr>
		<tr valign="top">
			<td class="formlabel"><label for="work_phone"><?php echo _SECONDARY_PHONE; ?>:</label></td>
			<td>
        <div style="display: inline-block; vertical-align: top; padding-right: 4px; padding-top: 2px;">
          <input type="text" name="work_phone" id="work_phone" size="20"
             maxlength="20" class="inputbox<?php
        if (A25_DI::PlatformConfig()->requireSecondaryPhone) {
            echo ' required" tmt:required="true';
        }
            ?>" onBlur="fixPhone(this)"
             tmt:errorclass="invalid"
             tmt:message="Please enter your work phone number." value="" />
        </div>
        <?php self::fireRegistrationFormAfterEachPhoneNumber("work");?></td>
		</tr>
        <?php self::fireStudentFormContactInfo();?>
		<tr>
			<td colspan="2">
			<h3><?php echo _LICENSE_INFORMATION; ?></h3>
			</td>
		</tr>
        <?php if(A25_DI::PlatformConfig()->collectLicenseStatus) { ?>
		<tr>
			<td class="formlabeltop"><label><?php echo _CURRENT_LICENSE_STATUS; ?>: <img src="<?php echo A25_Link::To('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></label></td>
      <td><span class="radio"><?php echo $lists['license_status']; ?></span>
			</td>
		</tr>
        <?php } ?>
		</table>
        <div id="yesDLForm" <?php if (A25_DI::PlatformConfig()->collectLicenseStatus) {
            echo 'style="display:none;"'; }?>>
		<table width="100%" cellpadding="0" cellspacing="6" border="0">
		<tr>
			<td class="formlabel"><label><?php echo _LICENSE_ISSUING_STATE; ?>:</label></td>
			<td><?php echo $lists['license_state']; ?><img src="<?php echo A25_Link::To('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></td>
		</tr><? self::fireAfterLicenseIssuingStateRegister(); ?>
		</table>
		</div>
		<table width="100%" cellpadding="0" cellspacing="6" border="0">
		<tr>
			<td colspan="2">
			<h3><?php echo _OTHER_INFORMATION; ?></h3>
			</td>
		</tr>
		<tr>
			<td class="formlabeltop"><label for="gender"><?php echo _SEX; ?>: <img src="<?php echo A25_Link::To('/includes/js/tmt_validator/images/required.gif') ?>" border="0" width="10" height="8" align="absmiddle" /></label></td>
      <td><span class="radio"><?php echo $lists['gender']; ?></span></td>
		</tr>
		<tr>
			<td class="formlabeltop"><label for="special_needs"><?php echo _PLEASE_SPECIFY_ANY; ?>:</label></td>
			<td><textarea name="special_needs" id="special_needs" cols="24" rows="5"></textarea></td>
		</tr>
        <? self::fireAfterSpecialNeeds(); ?>
		<tr>
			<td></td>
			<td><br /><input type="submit" value="<?php echo _CONTINUE; ?>" /></td>
		</tr>
		</table>
		</form>
		<?php
        echo '</div>';
        echo '</div>';
    }
    
    private static function fireAfterZipCode()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_Employer) {
            $listener->afterZipCode();
            }
        }
    }

    private static function fireAfterLicenseIssuingStateRegister()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_LicenseInfo) {
            $listener->afterLicenseIssuingStateRegister();
            }
        }
    }

    private static function fireRegistrationFormAfterEachPhoneNumber($name)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_PhoneNumbers) {
                $listener->registrationFormAfterEachPhoneNumber($name);
            }
        }
    }

    private static function fireStudentFormContactInfo()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_ContactInfo) {
                $listener->studentFormContactInfo();
            }
        }
    }

    private static function fireAfterSpecialNeeds()
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_RegisterFormOtherInformation) {
                $listener->afterSpecialNeeds();
            }
        }
    }
}
