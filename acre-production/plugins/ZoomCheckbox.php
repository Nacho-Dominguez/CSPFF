<?php

class A25_Plugin_ZoomCheckbox implements A25_ListenerI_MakeEnrollment,
    A25_ListenerI_StudentConfirmationFields
{
  public function afterReasonForEnrollment(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if ($this->showZoomCheckbox($course))
    {
      ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin: 12px 0px;">
        <input type="checkbox" tmt:minchecked="1" tmt:errorclass="invalid"
            accept="" name="zoom_checkbox"
            tmt:message="Please acknowledge the Zoom protocols checkbox.">
        <?php echo $this->zoomMessage(); ?>
        </input>
      </div><?php
    }
  }
  
  public function afterEnrollInCourse(A25_Record_Enroll $enroll)
  {
    $student = $enroll->Student;
    if ($_REQUEST['zoom_checkbox'] == 'on')
    {
      $student->createCheckboxIfNecessary($this->zoomMessage());
    }
  }
  
  private function showZoomCheckbox(A25_Record_Course $course)
  {
        return $course->Location->virtual;
  }
  
  private function zoomMessage()
  {
    return '<i>I acknowledge the Zoom class protocols</i><br/>
<p><b><u>ZOOM CLASS PROTOCOLS</u></b></p>
<p>To receive credit for this class, all students must follow the requirements below without
exception:</p>
<ul>
<li>&#128216; <b>Workbook Required:</b> You must have your workbook with you to participate.</li>
<li>&#x1F4BB; <b>Computer Required:</b> Students must use a computer equipped with a working video
camera and microphone. Cellphones, iPads and tablets are <b>NOT</b> permitted.</li>
<li>&#x1F6AB; <b>No Vehicles:</b> You may not attend class while in a vehicle, either as a driver or
passenger.</li>
<li>&#x23F1; <b>Class Duration:</b> The class is 240 minutes (4 hours). Full attendance is required to
receive credit.</li>
<li>&#x1F3A5; <b>Camera On at All Times:</b> Your full face must be visible and no more than two (2) feet
from the camera. You may only leave the screen during scheduled breaks.</li>
<li>&#x1F590; <b>Active Participation Required:</b> Students must remain alert, attentive, and engaged.
Sleeping, placing your head down, or talking to others during class is not permitted.</li>
<li>&#x1F507; <b>Quiet, Well-Lit Environment:</b> Sit upright at a desk or table in a distraction-free
space. No other people may be present.</li>
<li>&#x1F454; <b>Professional Conduct & Dress Code:</b> Students must conduct themselves in a virtual
class with the same professionalism and respect expected in an in-person setting.
Profanity, offensive language or gestures, threats, harassment, or disruptive behavior
will <b>NOT</b> be tolerated and may result in removal from class.<br/><br/>
Appropriate classroom attire is required at all times. Clothing with offensive language or
graphics, pajamas, revealing attire, or anything deemed unprofessional or distracting is
prohibited. Students are expected to maintain a respectful demeanor toward the
instructor and fellow participants throughout the entire session.</li>
<li>&#x1F6AD; <b>No Smoking, Vaping, or Alcohol Use.</b></li>
<li>&#x1F440; <b>Class Monitoring:</b> All classes are monitored throughout the entire session.</li>
</ul>
<hr style="background-color: #999; height: 3px;">
<p>&#x26A0;&#xFE0F; <b>Warning Policy</b></p>
<p>An instructor or class monitor may issue one warning for violation of any class protocol.</p>
<p>There will be no second warning. If the behavior continues after the warning is given, you will
be immediately removed from the class, and written documentation explaining the reason for
removal will be placed in your record.</p>
<p>Once you are removed from class, you are not permitted to reenter the session.</p>
<p style="font-weight: bold; color: #C00;">NO EXCEPTIONS.</p>';
  }
}
