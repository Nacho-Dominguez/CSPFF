<?php

class A25_Remind_InstructorsAboutUpcomingClasses_FirstReminder
extends A25_Remind_InstructorsAboutUpcomingClasses
{

    protected function markSent($course)
    {
        $course->instructor_reminder_sent = 1;
        $course->save();
    }

    protected function reminderWindow()
    {
        return A25_Functions::formattedDateTime(
                PlatformConfig::firstInstructorReminderHoursBefore . ' hours');
    }
    
    protected function reminderNumber()
    {
        return 1;
    }
}
