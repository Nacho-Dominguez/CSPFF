<?php

class A25_BusinessRules_Online extends A25_BusinessRules
{
    public function hasBeenAttended($enroll)
    {
        return in_array($enroll->status_id, A25_Record_Enroll::attendedStatusList());
    }

    public function courseDate($courseDate)
    {
        return time();
    }

    public function redirectIfAlreadyEnrolledMessage()
    {
        return;
    }

    public function tuitionAccrualDate($orderItem)
    {
        $order = $orderItem->Order;
        $enroll = $order->Enrollment;
        return A25_Functions::stringToDate($enroll->date_completed);
    }
}
