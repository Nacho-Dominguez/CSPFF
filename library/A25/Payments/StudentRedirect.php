<?php

namespace Acre\A25\Payments;

abstract class StudentRedirect implements PaymentRedirectInterface
{
    protected function sendLatePaymentNotification($order, $pay)
    {
        $student = $order->getStudent();
        $course = $order->getCourse();

        $recipient = \ServerConfig::latePaymentNotificationRecipientEmailAddress();
        if ($course->relatedIsDefined('Instructor')) {
            $instructor = $course->Instructor->email;
        }
        $subject = 'Late payment made';
        $body = "Student ID: $student->student_id<br/>
			$student->first_name $student->last_name<br/>
			<br/>
			Course date: $course->course_start_date<br/>
			Payment date: $pay->created<br/>
			Payment amount: $pay->amount";

        \A25_DI::Mailer()->mail($recipient, $subject, $body);
        if (!$course->isPast() && $order->Enrollment->isActive()) {
            \A25_DI::Mailer()->mail($instructor, $subject, $body);
        }
    }
}
