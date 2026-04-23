<?php
  
/**
 * We're not exactly sure what 'unavailable' meant, but there hasn't been
 * any students given this status since 2006.  It's best to just count it as
 * attended for accounting purposes. 
 */
class A25_EnrollmentStatus_Unavailable extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_unavailable;
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
    return false;
  }
  public function preEnrollmentEmail()
  {
    return true;
  }
  public function canCountAsPaid()
  {
    return true;
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
