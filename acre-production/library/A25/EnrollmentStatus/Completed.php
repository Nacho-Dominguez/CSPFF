<?php

class A25_EnrollmentStatus_Completed extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_completed;
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
    return true;
  }
  public function blocksOtherEnrollments()
  {
    return false;
  }
}
