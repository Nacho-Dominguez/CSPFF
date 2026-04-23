<?php

class A25_Plugin_MinimumPermitAge implements A25_ListenerI_MakeEnrollment,
    A25_ListenerI_StudentConfirmationFields,
    A25_ListenerI_StudentConfirmationWarning
{
  public function beforeCourseInfo(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if ($this->studentIsTooYoungToObtainPermit($student, $course))
    {
      ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin-bottom: 12px;">
        <span style="font-weight: bold; color: #ff0000;">WARNING!!!</span>
        <i>If you plan to take this course to obtain your Driver Awareness Permit, Colorado law requires that you take the course after the age of 15 years, 6 months.
          This course happens before you are 15 years, 6 months old.
          If you are taking the course to get your permit, <a href="<?php echo PlatformConfig::findACourseUrl() ?>">choose a course after <?php echo date('F j, Y', strtotime($student->date_of_birth . '+15 years, 6 months'));?></a></i>
        </input></div>
      <?php
    }
  }
  
  public function afterReasonForEnrollment(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if ($this->studentIsTooYoungToObtainPermit($student, $course))
    {
      ?><div style="border: solid #0000ee 1px; padding: 12px; background-color: #eeeeee; margin: 12px 0px;">
        <input type="checkbox" tmt:minchecked="1" tmt:errorclass="invalid"
            accept="" name="permit_checkbox"
            tmt:message="Please acknowledge that this course cannot be used to obtain your permit.">
        <?php echo $this->permitMessage(); ?>
        </input>
      </div><?php
    }
  }
  
  public function afterEnrollInCourse(A25_Record_Enroll $enroll)
  {
    $student = $enroll->Student;
    if ($this->studentIsTooYoungToObtainPermit($student, $enroll->Course)
        && $enroll->reason_id == A25_Record_ReasonType::reasonTypeId_ObtainEarlyPermit)
    {
			throw new A25_Exception_InvalidEntry("This course does not fulfill the DMV's driver education requirement to obtain your driving permit. "
          . "Please choose a different reason for enrollment.");
    }
    
    if ($_REQUEST['permit_checkbox'] == 'on')
    {
      $student->createCheckboxIfNecessary($this->permitMessage());
    }
  }
  
  private function studentIsTooYoungToObtainPermit(A25_Record_Student $student,
      A25_Record_Course $course)
  {
    if (strtotime($student->date_of_birth) >
        strtotime($course->course_start_date . ' -15 years, -6 months'))
    {
      return true;
    }
    return false;
  }
  
  private function permitMessage()
  {
    return '<i>I understand that, because I will be younger than 15 years, 6 months old on the course date, this course <b>does not</b> fulfill the DMV\'s driver education requirement to obtain my driving permit.</i>';
  }
}
