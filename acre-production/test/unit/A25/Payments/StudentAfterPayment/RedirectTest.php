<?php

namespace Acre\A25\Payments;

class RedirectTest extends \test_Framework_UnitTestCase
{
    private $redirector;
    private $mailer;
    private $course;
    private $processor;
    private $order;
    private $pay;

    public function setUp()
    {
        parent::setUp();
        $this->redirector = $this->mock('\A25_Redirector');
        $this->mailer = $this->mock('\A25_Mailer');
        \A25_DI::setRedirector($this->redirector);
        \A25_DI::setMailer($this->mailer);

        $this->course = new \A25_Record_Course();
        $location = new \A25_Record_Location();
        $this->course->Location = $location;
        $location->late_fee_deadline = 48;
        $student = new \A25_Record_Student();
        $enroll = new \A25_Record_Enroll();
        $enroll->Course = $this->course;
        $student->Enrollments[] = $enroll;
        $this->order = new \A25_Record_Order();
        $this->order->Enrollment = $enroll;
        $this->pay = new \A25_Record_Pay();

        $instructor = new \A25_Record_User();
        $this->course->Instructor = $instructor;

        $this->processor = new StudentAimRedirect();
    }
    /**
     * @test
     */
    public function worksForFutureCourse()
    {
        $this->course->setCourseTime(strtotime("+3 days"));

        $this->redirector->expects($this->once())
           ->method('redirect')
           ->with(
               \A25_Link::to('account'),
               'Course Enrollment Completed - Thank You For Your Payment'
           );

        $this->processor->redirect($this->order, $this->pay);
    }
    /**
     * @test
     */
    public function redirectsForPastCourse()
    {
        $this->course->setCourseTime(strtotime("+1 days"));

        $this->redirector->expects($this->once())
           ->method('redirect')
           ->with(
               \A25_Link::to(
                   'after-late-payment'
               ),
               'Thank You For Your Payment'
           );

        $this->mailer->expects($this->exactly(2))
               ->method('mail');

        $this->processor->redirect($this->order, $this->pay);
    }
    /**
     * @test
     */
    public function sendsEmailForLatePayment()
    {
        $this->course->setCourseTime(strtotime("-1 days"));

        $this->mailer->expects($this->once())
               ->method('mail');

        $this->processor->redirect($this->order, $this->pay);
    }
}
