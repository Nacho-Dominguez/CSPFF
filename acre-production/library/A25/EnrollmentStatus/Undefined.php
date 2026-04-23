<?php
  
/**
 * This is simply a "Null Object" to act as a placeholder so that we don't have
 * to check for nulls as often.
 */
class A25_EnrollmentStatus_Undefined extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return null;
  }
  public function isActive()
  {
    return false;
  }
  public function isInactive()
  {
    return false;
  }
  public function occupiesSeat()
  {
    return false;
  }
  public function wasAttended()
  {
    return false;
  }
  public function allowsPaymentEffectsBeforeCourse() {
    return true;
  }
  public function allowsPaymentEffectsAfterCourse() {
    return true;
  }
  public function preEnrollmentEmail()
  {
    return true;
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
    return false;
  }
  public function blocksOtherEnrollments()
  {
    return false;
  }
}
