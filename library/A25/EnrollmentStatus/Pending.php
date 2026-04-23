<?php

/**
 * "Pending" has at least 3 different potential meanings:
 * - Completed the course, but has not paid yet
 * - Completed and paid, but has not received their certificate yet
 * - The course will be paid for by an agency afterwards, so the student has
 *   completed and received their certificate, but has an account balance of $0
 *   for the moment because they are not responsible for paying themselves.  But
 *   the agency still needs to pay for them.
 */
class A25_EnrollmentStatus_Pending extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_pending;
  }
  public function isActive()
  {
    return true;
  }
  public function occupiesSeat()
  {
    return true;
  }
  public function wasAttended()
  {
    return true;
  }
  public function allowsPaymentEffectsBeforeCourse() {
    return true;
  }
  public function allowsPaymentEffectsAfterCourse() {
    return true;
  }
  public function preEnrollmentEmail()
  {
    return false;
  }
  public function canCountAsPaid()
  {
    return false;
  }
  public function reservationIsTemporary()
  {
    return false;
  }
  public function isComplete()
  {
    return true;
  }
  public function blocksOtherEnrollments()
  {
    return false;
  }
}
