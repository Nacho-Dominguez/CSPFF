<?php

// This plugin is still in development and has not been activated.
class Controller_Administrator_InstructorTimesheet extends Controller
{ 
  public function executeTask()
  {
    echo '<h1>Instructor Timesheet For Marketing / Advertising / ITAG</h1>';
    echo 'Red fields are required';
    $request = new A25_Form_InstructorTimesheet();
    $request->run($_POST);
  }
}