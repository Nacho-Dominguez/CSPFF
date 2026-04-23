<?php
require_once(dirname(__FILE__) . '/../../../../includes/joomlaClasses.php');

class A25_OldCom_Student_Login
{
	public static function run( $email, $password) {

		if (trim($email == '')) {
			$msg = new stdClass();
			$msg->_error = "You must provide an email/user id to login!";
			return $msg;
		}
		if (trim($password == '')) {
			$msg = new stdClass();
			$msg->_error = "You must provide a 5 digit zip/postal code to login!";
			return $msg;
		}

    $student = self::getStudent($email);
    
		if (!self::checkPassword($student, $password)) {
			$msg = new stdClass();
			$msg->_error = "That username and password do not match. Please try again.";
			return $msg;
		}

		// Set student ID cookie
		$studentCookieName = mosHash( 'studentid'. A25_CookieMonster::sessionCookieName() );
		$studentCookieValue = $student->student_id;
		A25_CookieMonster::setSitewideCookie( $studentCookieName, $studentCookieValue);

		// Set hash cookie to validate student ID
		$hashCookieName = mosHash( 'hashid'. A25_CookieMonster::sessionCookieName() );
		$hashCookieValue = md5($student->student_id . $student->email);
		A25_CookieMonster::setSitewideCookie( $hashCookieName, $hashCookieValue);

		return 'Welcome back, ' . $student->first_name . ' ' . $student->last_name . ' (ID: ' . $student->student_id . ') ';
	}
  
  protected static function getStudent($email)
  {
    $query = Doctrine_Query::create()
        ->select('*')
        ->from('A25_Record_Student s')
        ->where('s.userid = ?', $email)
        ->orWhere('s.email = ?', $email);
    return $query->fetchOne();
  }
  
  protected static function checkPassword($student, $password)
  {
    if (!$student->salt_prefix)
      return false;
    
    $hash = A25_DI::Hasher()->hash($student->salt_prefix, $password);
    if ($student->student_id && $hash == $student->password) {
      return true;
    }
    return false;
  }
}
