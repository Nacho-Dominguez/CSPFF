<?php

class A25_EnrollmentStatus_Registered extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_registered;
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
    return true;
  }
  public function isComplete()
  {
    return false;
  }
  public function blocksOtherEnrollments()
  {
    return true;
  }
}
