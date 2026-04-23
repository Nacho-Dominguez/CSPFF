<?php

class A25_OldCom_Student_Logout
{
	public static function run() {

		// Set student ID cookie to empty string
		$studentCookieName = mosHash( 'studentid'. A25_CookieMonster::sessionCookieName() );
		A25_CookieMonster::setSitewideCookie( $studentCookieName, '', time());

		// Set hash cookie to empty string
		$hashCookieName = mosHash( 'hashid'. A25_CookieMonster::sessionCookieName() );
		A25_CookieMonster::setSitewideCookie( $hashCookieName, '', time());

		A25_DI::Redirector()->redirect('index.php','');
	}
}
?>
