<?php
require_once(dirname(__FILE__) . '/../../../../includes/sef.php');

class A25_OldCom_Student_RegisterForm
{
	public static function run( $userid, $dob, $course_id, $Itemid,
			$nexttask = 'account') {
		// Check for valid input:
		if (!$userid) {
			throw new A25_Exception_DataConstraint(
					'User ID cannot be blank.');
		}

		if (!preg_match('|^\d{2}/\d{2}/\d{4}$|', $dob))
			throw new A25_Exception_DataConstraint(
					'Date of Birth must be in the format MM/DD/YYYY.');

		// Make sure User ID is valid form:
		// This is more restrictive than current usernames, but we will restrict
		// the new ones to just this pattern.
		if (!preg_match('/^[A-Za-z0-9_]+$/', $userid)) {
			throw new A25_Exception_DataConstraint('User ID has '
					. 'invalid characters.  It may only have letters, numbers, '
					. "or '_'. It must be one word; no spaces are allowed.");
		}

		// Check User ID max length:
		if (strlen($userid) > 50) {
			throw new A25_Exception_DataConstraint(
					"User ID must be 50 or fewer characters.");
		}

		$course = new stdClass();

		if ($course_id) {
			$course = A25_Record_Course::retrieve( $course_id );
		}
        // Load language file
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            require_once(dirname(__FILE__) . '/../../../../translate/spanish.php');
        }
        else {
            require_once(dirname(__FILE__) . '/../../../../translate/english.php');
        }

		$tempStudentObjectForCheckingDOB = new A25_Record_Student();
		$tempStudentObjectForCheckingDOB->date_of_birth = $dob;
		$tempStudentObjectForCheckingDOB->checkAgeAtTimestamp(
				strtotime($course->course_start_date));

		$tempStudentObjectForCheckingDOB = null;

		$lists = array();

		// build list of states
		$lists['state'] = A25_SelectListGenerator::generateStateSelectList('state', ' class="inputbox" tmt:invalidindex="0" tmt:message="Please select your state of residence."', null);
		$lists['license_state'] = A25_SelectListGenerator::generateStateSelectList('license_state', ' id="license_state" class="inputbox" tmt:invalidvalue="required" tmt:message="Please enter your drivers license issuing state."', null);


		// build list of license_status
        if(A25_DI::PlatformConfig()->collectLicenseStatus) {
            $license_status = A25_DI::PlatformConfig()->licenseStatuses();
            $lists['license_status'] = mosHTML::radioList($license_status, 'license_status', ' tmt:required="true" tmt:message="Please select your drivers license status." onChange="checkDL(this)"');
        }

		// build list of gender
		$gender = array();
		$gender[] = mosHTML::makeOption('M',_MALE . '<br />');
		$gender[] = mosHTML::makeOption('F',_FEMALE);
		$lists['gender'] = mosHTML::radioList( $gender, 'gender', ' tmt:required="true" tmt:message="Please choose your sex."');

		if (A25_Record_Student::isUserIdAvailable($userid)) {
		   A25_OldCom_Student_RegisterFormHtml::registerForm( $course, $student, $lists, $userid, $dob, $nexttask, $Itemid );
		} else {
			throw new A25_Exception_DataConstraint("Sorry, that "
				. 'username is already taken.  Please choose a different one.');
		}
	}
}
