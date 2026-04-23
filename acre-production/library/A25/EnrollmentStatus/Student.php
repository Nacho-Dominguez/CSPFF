<?php

class A25_EnrollmentStatus_Student extends A25_EnrollmentStatus
{
  public function statusId()
  {
    return A25_Record_Enroll::statusId_student;
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
    return false;
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
    return true;
  }
}
