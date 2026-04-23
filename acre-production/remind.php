<?php
/*
 * This file sends the automated scheduled e-mails.
 *
 * This file should be kept simple enough that it can just be hand-tested.
 *
 * @todo-jon-medium-high - Turn this into an object-oriented design, so that we
 * can have different reminders for different kinds of sites.  When you start
 * this, talk it over with Thomas to come up with a design, but the basics will
 * be:
 * - A class called something like "A25_ReminderSender" that has a run() function,
 *   as well as functions such as sendPaymentReminders(), sendInstructorReminders(),
 *   etc.
 * - The run() function will be abstract in the parent class
 * - 2 children classes that implement run(), for Online and Physical Location
 *   programs.  The online() one probably won't have much of anything in run(),
 *   and the Physical Location one will probably call most, or all, of the
 *   functions.
 * - We will use our abstract A25_Factory to decide which version it instantiate,
 *   much like we do for A25_View_Student_Account.
 */
define('_VALID_MOS',1);
require_once(dirname(__FILE__) . '/autoload.php');
require_once(dirname(__FILE__) . '/includes/joomla.php');

if (!A25_DI::PlatformConfig()->sendReminders) {
    exit;
}

$firstInstructorReminder = new A25_Remind_InstructorsAboutUpcomingClasses_FirstReminder();
$count = $firstInstructorReminder->send();
echo "Reminded instructors with first reminder for $count upcoming courses.\n";
$secondInstructorReminder = new A25_Remind_InstructorsAboutUpcomingClasses_SecondReminder();
$count = $secondInstructorReminder->send();
echo "Reminded instructors with second reminder for $count upcoming courses.\n";

$firstReminder = new A25_Remind_Students_Payments_FirstReminder();
$count = $firstReminder->send();
echo "Reminded $count students of payment due with first reminder.\n";
$secondReminder = new A25_Remind_Students_Payments_SecondReminder();
$count = $secondReminder->send();
echo "Reminded $count students of payment due with second reminder.\n";

$kickout = new A25_Remind_Students_KickOut();
$count = $kickout->send();
echo "Kicked out $count students who had not paid.\n";

if (A25_DI::PlatformConfig()->sendClassReminder) {
    $firstUpcomingReminder = new A25_Remind_Students_UpcomingClass_FirstReminder();
    $count = $firstUpcomingReminder->send();
    echo "Reminded $count students of upcoming class with first reminder.\n";
    $secondUpcomingReminder = new A25_Remind_Students_UpcomingClass_SecondReminder();
    $count = $secondUpcomingReminder->send();
    echo "Reminded $count students of upcoming class with second reminder.\n";
}
