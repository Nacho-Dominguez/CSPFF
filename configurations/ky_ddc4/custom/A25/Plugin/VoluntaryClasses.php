<?php

class A25_Plugin_VoluntaryClasses implements A25_ListenerI_MakeEnrollment
{
    // This is written specifically for KY's voluntary courses.  The course types
    // code needs to be rewritten, then this plugin can be improved.
    public function afterEnrollInCourse(A25_Record_Enroll $enroll)
    {
        $course = $enroll->Course;
        if ($course->course_type_id == Config_CourseTypes::Voluntary
            && $enroll->reason_id == A25_Record_ReasonType::reasonTypeId_CourtOrdered)
        {
              throw new A25_Exception_InvalidEntry('This class is for voluntary students only.'
            . 'Please <a href="'. PlatformConfig::findACourseUrl()
				. '">choose a different class</a>.');
        }
    }
}
