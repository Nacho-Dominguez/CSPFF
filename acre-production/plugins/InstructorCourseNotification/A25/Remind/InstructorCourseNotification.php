<?php

class A25_Remind_InstructorCourseNotification extends A25_Remind
{ 
	protected function whom()
	{
		$q = Doctrine_Query::create()
			->select('*')
			->from('A25_Record_Course c')
			->leftJoin('c.Instructor i1')
			->leftJoin('c.Instructor2 i2')
			->Where('c.course_start_date > ?', A25_Functions::formattedDateTime());
		return $q->execute();
	}
  
    protected function sendToIndividual($course)
    {
        if ($course->instructor_id > 0 && $course->instructor_id != $course->instructor_notified) {
            $course->Instructor->sendMessage(self::emailSubject(),
                    self::emailBody($course));
            $course->instructor_notified = $course->instructor_id;
        }
        if ($course->instructor_2_id > 0 && $course->instructor_2_id != $course->instructor_2_notified) {
            $course->Instructor2->sendMessage(self::emailSubject(),
                    self::emailBody($course));
            $course->instructor_2_notified = $course->instructor_2_id;
        }
        $course->save();
    }

	/**
	 * @return string
	 */
	public static function emailSubject()
	{
		return PlatformConfig::courseTitleHtml() . ': You have been assigned a course';
	}

	/**
	 * @return string
	 */
	public static function emailBody(A25_Record_Course $course)
	{
		return "This is an automated notification that you have been assigned" .
			" to teach a course:\n\n" . $course->getFormattedDateTime() . "\n" .
			$course->getLocationName();
	}
}