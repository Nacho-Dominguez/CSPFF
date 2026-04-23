<?php

/**
 * This file is part of the deprecated "Joomla controller" approach.  Now, we
 * use the Controller class, instead.  For examples, see /library/Controller/.
 * Do not add new tasks to this file, make subclasses of Controller instead.
 */

/**
 * class {@link HTML_course}
 */
require_once( $mainframe->getPath( 'front_html' ) );

/**
 * class {@link AUTH_student}.
 */
require_once( $mosConfig_absolute_path . '/administrator/components/com_student/student.auth.php' );

Course::run();

class Course {
	/**
	 * The switchboard for com_course.
	 *
	 * @global int The number of rows to display.
	 * @global string The task to execute.
	 * @global gacl_api I think it has something to do with access rights.
	 * @global A25_Record_User The current user.
	 */
	function run() {
		global $mosConfig_list_limit, $task;

		$studentCookieName = mosHash( 'studentid'. A25_CookieMonster::sessionCookieName() );
		$student_id = (int) mosGetParam( $_COOKIE, $studentCookieName, 0 );
		$course_id = $_REQUEST['course_id'];

		switch( $task ) {
			case "browse":
				header( "HTTP/1.1 301 Moved Permanently" );
				header( "Status: 301 Moved Permanently" );
				header( "Location: " . A25_Link::withoutSef('find-a-course') );
				exit(0);
				break;

			case "confirm":
				A25_DoctrineRecord::$disableSave = true;
				AUTH_student::checkStudent($student_id, $course_id, $task);
				A25_Functions::checkCourse($course_id);
				Course::confirm($course_id, $student_id);
				break;

			case "receipt":
				A25_DoctrineRecord::$disableSave = true;
				AUTH_student::checkStudent( $student_id, $course_id, $task );
				$order_id = (int) mosGetParam( $_GET, 'order_id', 0 );
				Course::receipt( $order_id );
				break;

			case "find":
			default:
				header( "HTTP/1.1 301 Moved Permanently" );
				header( "Status: 301 Moved Permanently" );
				header( "Location: " . A25_Link::withoutSef('find-a-course') );
				exit(0);
				break;
		}
	}

	public static function confirm( $course_id, $student_id )
	{
		$course = A25_Record_Course::retrieve($course_id);
		$student = A25_Record_Student::retrieve($student_id);

		$courseRunner = new A25_CourseRunner($student, $course);
		$courseRunner->checkIfEnrollmentAllowed();

		$lists = array();
		$lists['heard_id'] = $course->Location->hearAboutList($isAdmin = false);
		$reasonListGenerator = new A25_MethodObject_GenerateReasonList(
				$course->Location, $student, $course);
		$lists['reason_id'] = $reasonListGenerator->reasonList($isAdmin = false);

		// build list of referring courts
		if (PlatformConfig::allowCourtReferrals)
			$lists['court_id'] = A25_SelectListGenerator::generateCourtSelectList(
				'court_id',
				'id="court_id" style="max-width: 100%;" tmt:invalidvalue="required" tmt:message="Please select your referring court." onChange="checkCourt(this)"',
				null);

    $factory = A25_DI::Factory()->ReasonForEnrollment();
		$factory->reasonForEnrollment($course, $lists, $student);
	}

	/**
	 * Show a registration receipt
	 *
	 * @param integer $order_id
	 * @return void
	 *
	 * @author Christiaan van Woudenberg
	 * @version August 16, 2006
	 */
	function receipt($order_id) {
		$order = A25_Record_Order::retrieve( $order_id );

		HTML_course::receipt($order->Enrollment);
	}
}
