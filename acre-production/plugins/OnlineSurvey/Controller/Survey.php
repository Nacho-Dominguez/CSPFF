<?php

class Controller_Survey extends Controller
{ 
  public function executeTask()
  {
    $enroll_id = intval($_GET['id']);
    
    $enroll = A25_Record_Enroll::retrieve($enroll_id);
    
    $enroll->was_survey_visited = true;
    $enroll->save();
    
    A25_DI::Redirector()->redirectAbsolute($this->surveyLink($enroll));
  }
  
  private function surveyLink(A25_Record_Enroll $enroll)
  {
    $enroll_id = $enroll->xref_id;
    
    $course_id = $enroll->course_id;
    
    $course = $enroll->Course;
    $instructor_id = $course->instructor_id;
    $instructor_2_id = $course->instructor_2_id;
    $location_id = $course->Location->location_id;
    
    $student = $enroll->Student;
    
    return 'https://www.surveymonkey.com/s/AliveAt25CO?enroll_id=' . $enroll_id
        . '&course_id=' . $course_id . '&location_id=' . $location_id
        . '&instructor_id=' . $instructor_id . '&instructor2_id=' . $instructor_2_id
        . '&gender=' . $student->gender . '&age=' . $student->age()
        . '&reason_id=' . $enroll->reason_id;
  }
}