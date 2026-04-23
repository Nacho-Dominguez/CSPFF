<?php

class A25_EnrollmentStatus_NoShow extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_noShow;
  }
  public function isActive()
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
    return false;
  }
  public function allowsPaymentEffectsAfterCourse() {
    return false;
  }
  public function preEnrollmentEmail()
  {
    return false;
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
    return false;
  }
  public function blocksOtherEnrollments()
  {
    return false;
  }
}
