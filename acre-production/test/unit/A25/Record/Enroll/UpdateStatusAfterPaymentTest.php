<?php

class test_unit_A25_Record_Enroll_UpdateStatusAfterPaymentTest
    extends test_Framework_UnitTestCase
{
  /**
   * @test
   */
  public function whenPendingAndCourseIsPast_makesCompleted()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_pending,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  /**
   * @test
   */
  public function whenPendingAndCourseIsFuture_makesStudent()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_pending,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_student,
    ));
  }
  /**
   * @test
   */
  public function whenPendingButStillNeedsSurchargeConfirmedAndCourseIsPast_leavesPending()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_pending,
      'course_day' => 'tomorrow',
      'waiting_on_waived_confirmation' => true,
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_pending,
    ));
  }
  /**
   * @test
   */
  public function whenRegisteredAndCourseIsPast_makesCompleted()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_registered,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  /**
   * @test
   */
  public function whenRegisteredAndCourseIsFuture_makesStudent()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_registered,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => true,
      'send_specialneeds_email' => true,
      'afterwards_status' => A25_Record_Enroll::statusId_student,
    ));
  }
  /**
   * @test
   */
  public function whenRegisteredButStillNeedsSurchargeConfirmedAndCourseIsPast_makesPending()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_registered,
      'course_day' => 'yesterday',
      'waiting_on_waived_confirmation' => true,
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_pending,
    ));
  }
  /**
   * @test
   */
  public function whenRegisteredButStillNeedsSurchargeConfirmedAndCourseIsFuture_makesPending()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_registered,
      'course_day' => 'tomorrow',
      'waiting_on_waived_confirmation' => true,
      'send_enrollment_email' => true,
      'send_specialneeds_email' => true,
      'afterwards_status' => A25_Record_Enroll::statusId_pending,
    ));
  }
  /**
   * @test
   */
  public function whenNullStatusAndCourseIsPast_makesCompleted()
  {
    $this->execute(array(
      'initial_status' => null,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  /**
   * @test
   */
  public function whenNullStatusAndCourseIsFuture_makesStudent()
  {
    $this->execute(array(
      'initial_status' => null,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => true,
      'send_specialneeds_email' => true,
      'afterwards_status' => A25_Record_Enroll::statusId_student,
    ));
  }
  /**
   * @test
   */
  public function whenStudentAndCourseIsPast_makesCompleted()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_student,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  /**
   * @test
   */
  public function whenStudentAndCourseIsFuture_leavesStudent()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_student,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_student,
    ));
  }
  /**
   * @test
   */
  public function whenNoShowAndCourseIsPast_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_noShow,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_noShow,
    ));
  }
  /**
   * @test
   */
  public function whenNoShowAndCourseIsFuture_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_noShow,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_noShow,
    ));
  }
  /**
   * @test
   */
  public function whenCanceledAndCourseIsPast_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_canceled,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_canceled,
    ));
  }
  /**
   * @test
   */
  public function whenCanceledAndCourseIsFuture_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_canceled,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_canceled,
    ));
  }
  /**
   * @test
   */
  public function whenKickedOutAndCourseIsPast_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_kickedOut,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_kickedOut,
    ));
  }
  /**
   * @test
   */
  public function whenKickedOutAndCourseIsFuture_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_kickedOut,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_kickedOut,
    ));
  }
  /**
   * @test
   */
  public function whenUnavailableAndCourseIsPast_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_unavailable,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_unavailable,
    ));
  }
  /**
   * @test
   */
  public function whenUnavailableAndCourseIsFuture_makesStudent()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_unavailable,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => true,
      'send_specialneeds_email' => true,
      'afterwards_status' => A25_Record_Enroll::statusId_student,
    ));
  }
  /**
   * @test
   */
  public function whenUnavailableAndCourseIsPastButUnconfirmedWaive_leavesUnavailable()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_unavailable,
      'course_day' => 'yesterday',
      'waiting_on_waived_confirmation' => true,
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_unavailable,
    ));
  }
  /**
   * @test
   */
  public function whenUnavailableAndCourseIsFutureButUnconfirmedWaive_makesPending()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_unavailable,
      'course_day' => 'tomorrow',
      'waiting_on_waived_confirmation' => true,
      'send_enrollment_email' => true,
      'send_specialneeds_email' => true,
      'afterwards_status' => A25_Record_Enroll::statusId_pending,
    ));
  }
  /**
   * @test
   */
  public function whenFailedAndCourseIsPast_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_failed,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_failed,
    ));
  }
  /**
   * @test
   */
  public function whenFailedAndCourseIsFuture_doesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_failed,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_failed,
    ));
  }
  /**
   * @test
   */
  public function whenAlreadyCompletedAndCourseIsPast_DoesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_completed,
      'course_day' => 'yesterday',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  /**
   * @test
   */
  public function whenAlreadyCompletedAndCourseIsFuture_DoesNothing()
  {
    $this->execute(array(
      'initial_status' => A25_Record_Enroll::statusId_completed,
      'course_day' => 'tomorrow',
      'send_enrollment_email' => false,
      'send_specialneeds_email' => false,
      'afterwards_status' => A25_Record_Enroll::statusId_completed,
    ));
  }
  private function execute(array $scenario)
  {
    $enroll = $this->getMock('A25_Record_Enroll', array('sendEnrollmentEmail',
        'sendSpecialNeedsEmail', 'waitingOnWaivedFeeConfirmation'));
    $course = new A25_Record_Course();
    $enroll->Course = $course;
    
    $student = new A25_Record_Student();
    $enroll->Student = $student;
    
    $enroll->status_id = $scenario['initial_status'];
    $course->course_start_date = A25_Functions::formattedDateTime($scenario['course_day']);
    
    if ($scenario['send_enrollment_email'])
      $expects = $this->once();
    else
      $expects = $this->never();
    $enroll->expects($expects)->method('sendEnrollmentEmail');
    
    if ($scenario['send_specialneeds_email'])
      $expects = $this->once();
    else
      $expects = $this->never();
    $enroll->expects($expects)->method('sendSpecialNeedsEmail');
    
    $call = $enroll->expects($this->once())->method('waitingOnWaivedFeeConfirmation');
    if ($scenario['waiting_on_waived_confirmation'])
      $call->will($this->returnValue(true));
    else
      $call->will($this->returnValue(false));
    
    $enroll->updateStatusAfterPayment();
    
    $this->assertEquals($scenario['afterwards_status'], $enroll->status_id);
  }
}