<?php

class A25_Remind_InstructorsAboutUpcomingClasses_SecondReminder
extends A25_Remind_InstructorsAboutUpcomingClasses
{

    protected function markSent($course)
    {
        $course->instructor_reminder_sent = 2;
        $course->save();
    }

    protected function reminderWindow()
    {
        return A25_Functions::formattedDateTime(
                PlatformConfig::secondInstructorReminderHoursBefore . ' hours');
    }
    
    protected function reminderNumber()
    {
        return 2;
    }
}
