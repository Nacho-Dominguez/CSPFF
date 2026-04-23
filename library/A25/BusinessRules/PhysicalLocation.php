<?php

class A25_BusinessRules_PhysicalLocation extends A25_BusinessRules
{
	public function hasBeenAttended($enroll)
	{
    if (!$enroll->courseIsPast())
      return false;
    
		return in_array($enroll->status_id, A25_Record_Enroll::attendedStatusList());
	}
  
  public function courseDate($courseDate)
  {
    return $courseDate;
  }
  
  public function redirectIfAlreadyEnrolledMessage()
  {
    return 'You are already enrolled in a course. To register for a different course, please cancel your current enrollment and then register for a new course.';
  }
  
  public function tuitionAccrualDate($orderItem)
  {
    return A25_Functions::stringToDate($orderItem->courseDateTime());
  }
}
