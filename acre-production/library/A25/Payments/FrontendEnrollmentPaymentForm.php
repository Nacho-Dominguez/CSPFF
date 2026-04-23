<?php

namespace Acre\A25\Payments;

abstract class FrontendEnrollmentPaymentForm extends PaymentForm
{
    protected $student;
    protected $enroll;
    protected $generator;

    public function __construct($student)
    {
        $this->student = $student;
        $this->enroll = $this->student->getActiveEnrollment();
        if ($this->enroll == null) {
            $this->enroll = $this->student->getNewestEnrollment();
        }
    }

    public function getStudent()
    {
        return $this->student;
    }

    public function getEnroll()
    {
        return $this->enroll;
    }

    protected function setDecorators()
    {
        $this->form->setDecorators(
            array(
                array(
                    'ViewScript',
                    array('viewScript' => 'creditCardPaymentViewScript.phtml')
                )
            )
        );
    }

    public function preFormContent()
    {
        $this->kickOutWarning();
        $this->displayOrderSummary();
    }

    protected function kickOutWarning()
    {
        if ($this->enroll) {
            $paymentTimer = new \A25_PaymentTimer($this->enroll);
            $this->output .= $paymentTimer->insert();
        }
    }

    protected function displayOrderSummary()
    {
        $this->generator = new \A25_ListPayOpts(
            $this->student->getAccountBalance(),
            $this->enroll->Order,
            $this->enroll->Course
        );

        $this->output .= $this->generator->orderSummary();
    }
}
