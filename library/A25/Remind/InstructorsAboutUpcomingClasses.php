<?php

abstract class A25_Remind_InstructorsAboutUpcomingClasses extends A25_Remind
{ 
	/**
	 * Instructors who should be sent reminder emails about their upcoming courses
   * 
   * @return array of A25_Record_User
	 */

	protected function whom()
	{
		$q = Doctrine_Query::create()
			->select('*')
			->from('A25_Record_Course c')
			->innerJoin('c.Instructor i1')
			->leftJoin('c.Instructor2 i2')
			->where('c.course_start_date < ?', $this->reminderWindow())
			->andWhereIn('c.status_id', A25_Record_Course::$activeStatuses)
			->andWhere('c.course_start_date > ?', A25_Functions::formattedDateTime())
			->andWhere('c.instructor_reminder_sent < ?', $this->reminderNumber());
		return $q->execute();
	}
    
    abstract protected function reminderWindow();
    
    abstract protected function markSent($course);
    
    abstract protected function reminderNumber();
    
    protected function sendToIndividual($course)
    {
        $this->markSent($course);
        if ($course->instructor_id > 0) {
            $course->Instructor->sendMessage(self::emailSubject(),
                self::emailBody($course));
        }
        if ($course->instructor_2_id > 0) {
            $course->Instructor2->sendMessage(self::emailSubject(),
                self::emailBody($course));
        }
        if ($course->Location->virtual == 1) {
            foreach (A25_DI::PlatformConfig()->copyInstructorReminderForVirtualClasses as $email) {
                A25_DI::Mailer()->mail($email,self::emailSubject(),self::emailBody($course),0);
            }
        }
        $course->save();
    }

	/**
	 * @return string
	 */
	public static function emailSubject()
	{
		return PlatformConfig::courseTitleHtml() . ' Course Reminder';
	}

	/**
	 * @return string
	 */
	public static function emailBody(A25_Record_Course $course)
	{
		$return = "This is an automated reminder that you are scheduled to teach" .
			" a course:\n\n" . $course->getFormattedDateTime() . "\n" .
			$course->getLocationName();
        if ($course->Location->virtual == 1  && $course->zoom_link) {
            $return .= "\n\nZoom Link: " . $course->zoom_link;
        }
        return $return;
	}
}