<?php

/**
 * @todo-jon-small-low - Move UpcomingClassBody.phtml and
 * UpcomingClassAltBody.phtml into UpcomingClassBodies/body.phtml and
 * alt_body.phtml, respectively.  Basically, they should be done like
 * DonationReceipt in the Donation Plugin was done.
 */
abstract class A25_Remind_Students_UpcomingClass extends A25_Remind_Students
{ 
    protected function subject()
    {
        return A25_EmailContent::wrapSubject('Class reminder');
    }

    abstract protected function beginningOfReminderWindow();

    abstract protected function endOfReminderWindow();

    protected static function enrolledBefore()
    {
      return A25_Functions::formattedDateTime('-' . PlatformConfig::timeEnrolledBeforeReminderSent . ' hours');
    }
}