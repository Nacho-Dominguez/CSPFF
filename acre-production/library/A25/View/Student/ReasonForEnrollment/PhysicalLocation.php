<?php

class A25_View_Student_ReasonForEnrollment_PhysicalLocation
    extends A25_View_Student_ReasonForEnrollment
{
  protected function showCourseInfo($course)
  {
    return $course->showCourseInfo('timelocation');
  }
  protected function differentCourse($course)
  {
    $return = ' or
		<a href="' . PlatformConfig::findACourseUrl() . '">';
    if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
        $return .= 'elige un curso diferente';
    }
    else {
        $return .= 'choose a different course';
    }
    $return .= '</a>';
    return $return;
  }
}
