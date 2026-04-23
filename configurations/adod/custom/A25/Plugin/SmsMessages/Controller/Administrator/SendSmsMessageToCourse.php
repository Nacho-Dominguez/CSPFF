<?php

class Controller_Administrator_SendSmsMessageToCourse extends Controller
{ 
  public function executeTask()
  {
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    
    $course_id = intval($_REQUEST['course_id']);
    $number_of_students = A25_Plugin_SmsMessages::queryStudentsWhoCanReceiveInCourse($course_id)->count();
    
    echo '<div style="padding: 24px; border: 1px solid #ccc; background-color: #f6f6f6;
         text-align: center; display: inline-block; margin-top: 24px; border-radius: 10px;
         box-shadow: 0px 2px 2px #aaa;">';
    echo '<h3 style="margin: 0px; color: #333; font-size: 16px;">SMS text message </h3>';
    echo '<p style="margin-top: 0px; max-width: 300px; margin-left: auto;
         margin-right: auto; color: #666">
        will be sent to the <span style="font-weight: bold; color: green;">'
        . $number_of_students . '</span> students in the class who elected to receive text messages.
      </p>';
    
    $request = new A25_Form_SendSmsMessage($course_id);
    $request->run($_POST);
    
    echo '</div>';
  }
}