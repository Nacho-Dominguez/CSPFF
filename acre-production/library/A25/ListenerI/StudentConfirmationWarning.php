<?php
interface A25_ListenerI_StudentConfirmationWarning
{
  public function beforeCourseInfo(A25_Record_Student $student,
      A25_Record_Course $course);
}