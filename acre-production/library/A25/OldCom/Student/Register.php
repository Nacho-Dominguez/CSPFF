<?php
require_once(dirname(__FILE__) . '/../../../../includes/joomlaClasses.php');

class A25_OldCom_Student_Register
{
	public static function run ($mosConfig_offset,$mosConfig_mailfrom,
			$mosConfig_fromname ) {

        if (A25_DI::PlatformConfig()->confirmLicenseNo) {
            if ($_POST["license_no"] != $_POST["confirm_license_no"]) {
                throw new A25_Exception_DataConstraint('License numbers do not match');
            }
        }
        if ((int) $_POST["license_status"] != A25_Record_Student::licenseStatus_unlicensed) {
            if ($_POST["license_state"] == "required") {
                throw new A25_Exception_DataConstraint('License state is blank');
            }
        }
        if (A25_DI::PlatformConfig()->requireEmail) {
            if (!$_POST["email"]) {
                throw new A25_Exception_DataConstraint('Email address is required');
            }
        }
		$row = new A25_Record_Student();
		
		$_POST['date_of_birth'] = date('Y-m-d', strtotime($_POST['date_of_birth']));

		// This branch is not tested
		if (!$row->bind( $_POST )) {
			throw new A25_Exception_DataConstraint($row->getError());
		}

		$row->checkAndStore();

		// Set student ID cookie
		$studentCookieName = mosHash( 'studentid'. A25_CookieMonster::sessionCookieName() );
		$studentCookieValue = $row->student_id;
		A25_CookieMonster::setSitewideCookie($studentCookieName, $studentCookieValue);

		// Set hash cookie to validate student ID
		$hashCookieName = mosHash( 'hashid'. A25_CookieMonster::sessionCookieName() );
		$hashCookieValue = md5($row->student_id . $row->email);
		A25_CookieMonster::setSitewideCookie($hashCookieName, $hashCookieValue);

		// Send the registration email to the student.
		// This branch is not tested
		$recipient = $row->email; // student's email.
		if ($recipient) {
			$subject = self::subject();
			$body = 'Your account at ' . ServerConfig::staticHttpUrl() . ' has been created.
If you get redirected to our home page, be sure to click on your state before logging in.

Username: '. $row->userid .'
Password: Use your zip code';

			// Send email to user
			mosMail($mosConfig_mailfrom, $mosConfig_fromname, $recipient, $subject, $body, 0, null, 'aliveat25copies@gmail.com', null, A25_DI::PlatformConfig()->contactEmailAddress);
		}

		return 'Student account created.';
	}
  
  protected function subject()
  {
    return A25_EmailContent::wrapSubject('Registration Confirmation');
  }
}
