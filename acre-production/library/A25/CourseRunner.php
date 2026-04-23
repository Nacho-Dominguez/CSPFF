<?php

class A25_CourseRunner extends A25_StrictObject
{
	private $_course;
	private $_student;
	protected $_enroll;

	function __construct (A25_Record_Student $student, A25_Record_Course $course) {
		$this->_student = $student;
		$this->_course = $course;
	}

	public function enrollFromAdmin() {
		if ($this->studentHasBlockingEnrollments())
			$msg = "Student is already enrolled in a course.  That enrollment must be cancelled in order to enroll the student in a different course";
		else {
			$this->createEnrollment();
      $this->commitEnrollment();
			$balance = $this->_student->getAccountBalance();
			$msg = 'Student has successfully been enrolled. ';
			$course = A25_Record_Course::retrieve( $_REQUEST['course_id']);
			try {
				$this->_student->checkAgeAtTimestamp(strtotime($course->course_start_date));
			}catch(A25_Exception_DataConstraint $e) {
				$msg .= 'WARNING: Student will be '
						. $this->_student->age(strtotime($course->course_start_date))
						. ' on course date. ';
			}
			if ($balance <= 0)
				$msg .= 'The student had an account credit that was able to '
						. 'cover the cost of the enrollment.';
		}
		A25_DI::Redirector()->redirect( 'index2.php?option=com_student&task=viewA&id=' .
				$this->_student->student_id, $msg );
	}
  
	public function createEnrollment() {
		$this->makeTheEnrollment();
		return $this->_enroll;
	}
	public function commitEnrollment() {
		if ($this->_student->getAccountBalance() <= 0) {
			$this->_student->updateOrdersAndEnrollmentsAfterPayment();
		}

		$this->makeChangesPermanent();
	}

	protected function makeTheEnrollment() {
		$course = $this->_course;
		$student = $this->_student;

		if ($_REQUEST['is_late'] == null)
			$_REQUEST['is_late'] = false;

		$this->_enroll = $student->enrollInCourse($course, $_REQUEST['hear_about_id'],
				$_REQUEST['reason_id'], $_REQUEST['is_late'], $_REQUEST['court_id'],
				$_REQUEST['reason_other']);

		$this->fireAfterEnrollInCourse();
	}

	/**
	 * This is protected for testing only.  Otherwise it would be private.
	 */
	protected function makeChangesPermanent() {
		$this->_student->save();
    $this->fireDuringMakeChangesPermanent();
	}
	/**
	 * Returns true if student has enrollments that block new enrollments.
	 */
	protected function studentHasBlockingEnrollments() {
        if(A25_DI::PlatformConfig()->onlyOneEnrollmentAllowed) {
            $q = Doctrine_Query::create()
                ->select('xref_id')
                ->from('A25_Record_Enroll e')
                ->where('e.student_id = ?', $this->_student->student_id)
                ->andWhereIn('e.status_id', A25_Record_Enroll::blocksOtherEnrollmentsStatusList());
            return $q->count();
        }
        else {
            return false;
        }
	}
  
	/**
	 * Check student's current enrollments.  If student is already enrolled in
	 * a future course, redirect to student's enrollments tab.  We make sure to
	 * check the student status.
	 */
	public function redirectIfAlreadyEnrolled () {
		$active_enrollments = $this->studentHasBlockingEnrollments();
    $rules = A25_DI::Factory()->BusinessRules();

		if ($active_enrollments) {
			A25_DI::Redirector()->redirect('account', $rules->redirectIfAlreadyEnrolledMessage());
		}
	}
  
  public function checkIfEnrollmentAllowed()
  {
    $this->redirectIfAlreadyEnrolled();
    $this->_student->checkAgeAtTimestamp(strtotime($this->_course->course_start_date));

		//make sure the course is published! do not allow enrollments in unpublished courses.
		if ( $this->_course->published != 1 ) {
			throw new A25_Exception_IllegalAction('This course is not currently '
				. 'available for enrollment. Please select a different course '
				. 'to enroll in.');
		}
  }
  
	private function fireAfterEnrollInCourse()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_MakeEnrollment)
        $body = $listener->afterEnrollInCourse($this->_enroll);
    }
    return $body;
	}
  
  private function fireDuringMakeChangesPermanent()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_SaveEnrollment)
        $body = $listener->duringMakeChangesPermanent($this->_enroll);
    }
    return $body;
  }
}
