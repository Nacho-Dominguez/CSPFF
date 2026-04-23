<?php

class A25_Plugin_GLPCheckbox implements A25_ListenerI_MakeEnrollment,
    A25_ListenerI_StudentConfirmationFields,
    A25_ListenerI_StudentConfirmationWarning
{
  public function beforeCourseInfo(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if ($this->studentIsEligibleForGLP($student, $course))
    {
      ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin-bottom: 12px;">
        <span style="font-weight: bold; color: #ff0000;">WARNING!!!</span>
        <i>Graduated Licensing Program students must present their driver permit/license
            to the instructor. If you do not bring your permit/license you will
            not be able to attend the course.</i>
        </input></div>
      <?php
    }
    else
    {
        ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin-bottom: 12px;">
        <span style="font-weight: bold; color: #ff0000;">WARNING!!!</span>
        <i>In order to qualify for the Graduated Licensing Program you must be 21
            or under and have a license or permit. You are seeing this message because
            you are either unlicensed or over the age of 21.</i>
        </input></div>
      <?php
    }
  }
  public function afterReasonForEnrollment(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if ($this->studentIsEligibleForGLP($student, $course))
    {
      ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin: 12px 0px;">
        <input type="checkbox" tmt:minchecked="1" tmt:errorclass="invalid"
            accept="" name="glp_checkbox"
            tmt:message="Please acknowledge both Graduated Licensing Program checkboxes.">
        <?php echo $this->GLPMessage(); ?>
        </input>
      </div>
      <div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin: 12px 0px;">
        <input type="checkbox" tmt:minchecked="1" tmt:errorclass="invalid"
            accept="" name="glp_checkbox_2"
            tmt:message="Please acknowledge both Graduated Licensing Program checkboxes.">
        <?php echo $this->GLPMessage2(); ?>
        </input>
      </div><?php
    }
  }
  
  public function afterEnrollInCourse(A25_Record_Enroll $enroll)
  {
    $student = $enroll->Student;
    if ($_REQUEST['glp_checkbox'] == 'on')
    {
      $student->createCheckboxIfNecessary($this->GLPMessage());
    }
    if ($_REQUEST['glp_checkbox_2'] == 'on')
    {
      $student->createCheckboxIfNecessary($this->GLPMessage2());
    }
  }
  
  private function studentIsEligibleForGLP(A25_Record_Student $student,
      A25_Record_Course $course)
  {
        return A25_DrivingPermitDiscount::eligibleForPermit($student, $course);
  }
  
  private function GLPMessage()
  {
    return '<i>I understand that if I select the G.L.P. reason for enrollment but'
            . ' do not bring my permit/license'
            . ' I will not be able to attend the course.</i>';
  }
  
  private function GLPMessage2()
  {
    return '<i>I understand that if I select the G.L.P. reason for enrollment'
        . ' but do not cancel or reschedule at least 24 hours in advance and do'
        . ' not show up for the class that I will be charged a <span'
        . ' style="color: #C00; font-weight: bold;">$' . A25_DI::PlatformConfig()->noShowDiscountedFee . ' No Show fee</span> and'
        . ' that the fee must be paid before I will be given credit for completing a class.</i>';
  }
}
