<?php

class A25_Remind_Students_UpcomingClass_FirstReminder
extends A25_Remind_Students_UpcomingClass
{
    /**
     * @param A25_Record_Enroll $enroll
     * @return string
     */
    protected function body(A25_Record_Enroll $enroll)
    {
        ob_start();
        require dirname(__FILE__) . '/../' . A25_DI::PlatformConfig()->firstClassReminderBody;
        return ob_get_clean();
    }
    
    protected function markSent($enroll)
    {
        $enroll->sent_class_reminder = 1;
        $enroll->save();
    }
  
	/**
	 * Get the students to send to.
	 */
	protected function whom()
	{
		$q = Doctrine_Query::create()
			->select('*')
			->from('A25_Record_Enroll e')
			->innerJoin('e.Course c')
			->leftJoin('e.Student s')
			->where('c.course_start_date < ?', self::beginningOfReminderWindow())
			->andWhere('c.course_start_date > ?', self::endOfReminderWindow())
            ->andWhere(A25_Record_Enroll::active('e'))
            ->andWhere('e.date_registered < ?', self::enrolledBefore())
			->andWhere('e.sent_class_reminder = 0');
		return $q->execute();
	}
  
    protected function beginningOfReminderWindow()
    {
        return A25_Functions::formattedDateTime(
                PlatformConfig::classReminderMaxHoursBefore . ' hours');
    }

    protected function endOfReminderWindow()
    {
        return A25_Functions::formattedDateTime(
                PlatformConfig::classReminderMinHoursBefore . ' hours');
    }
}