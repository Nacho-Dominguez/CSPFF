<?php
interface A25_ListenerI_StudentConfirmationFields
{
  public function afterReasonForEnrollment(A25_Record_Student $student,
      A25_Record_Course $course);
}