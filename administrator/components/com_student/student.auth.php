<?php

class AUTH_student {
	/**
	 * Check a student_id cookie for authenticity.
	 *
	 * @param integer $student_id
	 * @param string $task
	 * @return array $range
	 *
	 * @author Christiaan van Woudenberg
	 */
	public static function checkStudent( $student_id, $course_id, $task ) {
		global $_config;
		if (!$student_id) {
			A25_DI::Redirector()->redirect(
					'index.php?option=com_student&task=loginForm&nexttask='
						. $task . '&course_id=' . $course_id . '&Itemid=20',
					'',
					303);
		}
		if (!$_config['studentCheck']) {
			$hashCookieName = mosHash( 'hashid'. A25_CookieMonster::sessionCookieName() );
			$student_hash = trim( mosGetParam( $_COOKIE, $hashCookieName, null ) );

			$student = A25_Record_Student::retrieve($student_id);

			if ($student_hash != md5($student->student_id . $student->email)) {
				A25_DI::Redirector()->redirect('index.php?option=com_student&task=logout&Itemid=20','Authentication Failed. Please log on again.');
			} else {
				$_config['studentCheck'] = true;
			}
		}
	}
}
