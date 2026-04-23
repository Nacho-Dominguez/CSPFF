<?php

class A25_Remind_Students_Payments_FirstReminder extends A25_Remind_Students_Payments
{

    protected function markSent($enroll)
    {
        $enroll->sent_payment_reminder = 1;
        $enroll->save();
    }

    /**
     * Upcoming enrollments with payment due
     *
     * @return array of A25_Record_Enroll
     */
    protected function whom()
    {
        $q = Doctrine_Query::create()
            ->select('*')
            ->from('A25_Record_Enroll e')
            ->innerJoin('e.Course c')
            ->leftJoin('e.Student s')
            ->where('e.date_registered > ?', $this->beginningOfReminderWindow())
            ->andWhere('e.date_registered < ?', $this->endOfReminderWindow())
            ->andWhere('c.course_start_date > ?', A25_Functions::formattedDateTime())
            ->andWhere(A25_Record_Enroll::active('e'))
            ->andWhere('s.calc_balance > 0')
            ->andWhere('e.sent_payment_reminder = 0');
        return $q->execute();
    }

    protected function beginningOfReminderWindow()
    {
        return A25_Functions::formattedDateTime('- ' .
        A25_DI::PlatformConfig()->firstPaymentReminderMaxHoursAfterEnrollment .
        ' hours');
    }

    protected function endOfReminderWindow()
    {
        return A25_Functions::formattedDateTime('- ' .
            A25_DI::PlatformConfig()->firstPaymentReminderMinTimeAfterEnrollment()
            . ' - 5 minutes');
    }
}
