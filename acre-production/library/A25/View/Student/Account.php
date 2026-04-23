<?php

abstract class A25_View_Student_Account extends A25_View_Student
{
    protected $student;
    protected $newest_enrollment;
    protected $current_enrollments;

    public function __construct($student)
    {
        $this->student = $student;
        $this->newest_enrollment = $this->student->getNewestEnrollment();
        $this->current_enrollments = $this->student->getCurrentEnrollments();
    }

    public function render()
    {
        A25_DI::HtmlHead()->append('
      <style type="text/css">
        .account-block {
          padding: 15px;
          background-color: white;
        }
        a.action_link {
          padding: 6px;
          border: 1px solid black;
          font-weight: bold;
          display: block;
          background: none;
          background-color: #efefff;
          border-radius: 5px;
          text-decoration: none;
          text-align: center;
        }
        a.action_link:hover {
          background-color: #dfdfff;
        }
        p {
          margin: 12px 0px 12px 0px;
        }
        br {
          clear: none
        }
        h2, h1 {
          color: #232;
        }
        .payment_due {
          float: right;
          max-width: 280px;
          margin-bottom: 12px;
          padding: 24px;
          background-color: #ffefdf;
          color: #333;
        }
        @media (max-width: 698px) {
          .payment_due {
            clear: left;
            float: left;
          }
        }
      </style>');
        ?>
      <h1 style="margin: 15px; font-size: 28px;">Your Account</h1>
		<div style="margin:15px;">
        <?php
        $this->loggedInBar();
        ?>
      <div style="clear: both; margin-top: 18px;">
      <div class="account-block" style="border: 1px solid #769E3B;">
        <?php
        echo $this->lateEnrollmentPrintMessage();
        echo $this->reservationMessage();
        $this->paymentDueInfo();
        $this->courseInfo();
        ?>
      </div>
        <?php
        $this->enrollmentHistory();
        $this->orders();
        $this->paymentHistory();
        $this->studentInfo();
        ?>
		</div>
      </div>
		<?php
    }

    abstract public function kickOutIfNecessary();

    private function loggedInBar()
    {
        ?>
      <div style="float: left; color: #363">Logged in as <?php
        $bar = '<b>' . $this->student->userid . '</b> &ndash; '
        .  $this->student->firstLastName();
        if ($this->student->email) {
            $bar .=' &ndash; ' .  $this->student->email;
        }
        $bar .=' &ndash; ' . $this->student->home_phone . ' &ndash; '
        . 'Student ID #: ' . $this->student->student_id;
        echo $bar;
    ?>
    </div>
		<div style="text-align:right; width: 100%;">
			<form action="index.php" method="get">
				<input type="submit" value="Sign Out" />
				<input type="hidden" name="option" value="com_student" />
				<input type="hidden" name="task" value="logout" />
				<input type="hidden" name="Itemid" value="20" />
			</form>
		</div>
    <?php
    }

    protected function lateEnrollmentPrintMessage()
    {
        if (!$this->newest_enrollment
        || !$this->newest_enrollment->hasFeeOfType(A25_Record_OrderItemType::typeId_LateFee)
        || $this->newest_enrollment->courseIsPast()
        || $this->student->getAccountBalance() > 0
        || $this->newest_enrollment->hasBeenAttended()
        || !$this->newest_enrollment->isActive()) {
            return;
        }

        return '<p style="font-style: italic">Since you enrolled within 24 hours of
      the class, please print out this page and bring it with you to the class,
      in case the instructor printed out the roster before you enrolled.  If you
      do not have access to a printer, please write down your student ID and this
      course ID on a sheet of paper and bring it to class:</p>
      <p style="margin-left: 24px;">Student ID: ' . $this->student->student_id . '<br/>Course ID: '
        . $this->newest_enrollment->course_id . '</p>';
    }

    abstract protected function courseInfo();

    abstract protected function upcomingCourseMessage($enroll);

    abstract protected function reservationMessage();

    protected function paymentDeadline()
    {
        $kick_out_time = strtotime($this->newest_enrollment->kick_out_date);
        if ($kick_out_time > strtotime('+24 hours')) {
            return 'by ' . date('g:i a', $kick_out_time)
            . ' on <b>' . date('l, F j', $kick_out_time) . '</b>';
        } elseif ($kick_out_time < strtotime('+1 hour')) {
                  $timezone = date_default_timezone_get();
                  date_default_timezone_set('UTC');
                  $return = 'within the next ' . intval(($kick_out_time - time()) / 60) . ' minutes';
                  date_default_timezone_set($timezone);
                  return $return;
        } else {
            $timezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $return = 'within the next ' . date('G', $kick_out_time - time()) . ' hours';
            date_default_timezone_set($timezone);
            return $return;
        }
    }

    protected function kickedOutMessage()
    {
        $course = $this->newest_enrollment->Course;
        $paymentoption = strtotime('- ' . $course->getSetting('register_cc_days')
        . ' days');
        $return = '<p>
    Your seat reservation has expired for the '
        . PlatformConfig::courseTitleFullHtml() . ' course on '
        . date('l, F j', strtotime($course->course_start_date)) .
        ' because payment has not been received.  You may <a href="'
        .  PlatformConfig::findACourseUrl()
        . '">register again for the same course or a different course</a>.  Please be sure to submit payment in time to preserve your seat in the course.
    </p>';
        if (strtotime($this->newest_enrollment->date_registered) < $paymentoption) {
            $return .= '<p>
      If you have already mailed in payment, please call our office at '
            . PlatformConfig::phoneNumber . '.
      </p>';
        }
        return $return;
    }

    abstract protected function enrollInACourse();

    protected function paymentDueInfo()
    {
        if (!$this->newest_enrollment) {
            return;
        }
        if ($this->student->getAccountBalance() <= 0) {
            return;
        }
        ?>
      <div class="payment_due">
        <?php
        $order = $this->newest_enrollment->Order;

        $this->student->addLateFeeToAccountBalance($this->student->getAccountBalance());
        $balance = $this->student->getAccountBalance();
        $course = $this->newest_enrollment->Course;
        $generator = new A25_ListPayOpts($balance, $order, $course);
        echo $generator->orderSummary();

        if (A25_DI::PlatformConfig()->acceptCreditCards) {
            echo '<a class="action_link" style="margin-top: 12px;"
                href="' . A25_Link::to(A25_DI::PlatformConfig()->paymentForm)
                . '">';
            if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
                echo 'Haz clic aqu&iacute; para pagar ahora con ';
            }
            else {
                echo 'Click here to pay now with ';
            }
            echo A25_DI::PlatformConfig()->acceptedCards . '</a>';
        }

        $this->paymentNotes();
        ?>
      </div>
        <?php
    }

    abstract protected function paymentNotes();

    protected function surchargeFootnote()
    {
        $surcharge = $this->newest_enrollment->surchargeLineItem();
        if ($surcharge && $surcharge->isActive()) {
            return '<p style="font-size: 10px; color: #999;">
    ** About the <i>DOR Fee</i> &ndash; '
            . PlatformConfig::surchargeFootnote($surcharge->unit_price) .
            '</p>';
        }
    }
    protected function lateFeeFootnote()
    {
        $course = $this->newest_enrollment->Course;
        if ($course->isPastLateFeeDeadline()) {
            return '<p style="font-size: 10px; color: #999;">'
            . $course->lateFeeFootnote() . '</p>';
        }
    }
    abstract protected function enrollmentHistory();

    protected function cancellationLinkLocation($enrollment)
    {
        $cancellation_location = sefReltoAbs('index.php?option=com_student&task=cancelenrollment&Itemid=20&xref_id=' . $enrollment->xref_id);
        $cancellation_text = 'Are you sure you want to cancel? \n\nIf you have already paid, your payment is good for up to 1 year from the original payment date and can be applied towards another course.\n\n Click OK to cancel this course.';

        return "javascript:if(confirm('" . $cancellation_text . "')) location='" . $cancellation_location . "'";
    }
    private function orders()
    {
        if (!A25_DI::PlatformConfig()->chargesForCourse()) {
            return;
        }
        if (!$this->newest_enrollment) {
            return;
        }
        ?>
		<h2 style="clear: both;">Orders</h2>
      <div class="wide_table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped">
		<thead>
			<tr>
				<th>Date/Time of Order</th>
				<th align="right">Total Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
        $enrollments = $this->student->Enrollments;
        foreach ($enrollments as $enroll) {
            $order = $enroll->Order;
            echo '<tr>'
                . '<td>' . $order->created . '</td>'
                . '<td align="right">';
            if (!$order->isActive()) {
                echo '(cancelled) ';
            }
            echo  '$' . number_format($order->totalAmount(), 2)
                . '</td>'
                . '</tr>'
                ;
        }

        if (!count($enrollments)) {
            echo '<tr><td colspan="5">You have not placed any orders yet.</tr>';
        }
        ?>
		</tbody>
		</table>
      </div>
        <?php
    }
    private function paymentHistory()
    {
        if (!A25_DI::PlatformConfig()->chargesForCourse()) {
            return;
        }
        ?>
		<h2>Payment/Refund History</h2>
		<p>Refunds are in <span style="color:red">Red</span></p>
      <div class="wide_table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped">
		<thead>
			<tr>
				<!-- th>Payment ID</th -->
				<!-- th>Order ID</th -->
				<th>Paid By</th>
				<th>Date/Time</th>
				<th>Type</th>
				<th align="right">Amount</th>
			</tr>
		</thead>
		<tbody>
		<?php
        foreach ($this->student->Payments as $pay) {
            // Display scholarship credits in red
            $style = '';
            if ($pay->refund_type_id > 0) {
                $style = ' style="color:red"';
            }

            echo '<tr>'
                . '<td><div'.$style.'>' . $pay->paid_by_name . '</div></td>'
                . '<td><div'.$style.'>' . $pay->created . '</div></td>'
                . '<td><div'.$style.'>' . $pay->PayType->pay_type_name . '</div></td>'
                . '<td align="right"><div'.$style.'>' . '$' . number_format($pay->amount, 2) . '</div></td>'
                . '</tr>'
                ;
        }

        if (!count($pay)) {
            echo '<tr><td colspan="5">There are no current payments.</td></tr>';
        }


        ?>
		</tbody>
		</table>
      </div>
        <?php
    }
    private function studentInfo()
    {
        ?>
      <h2>Your Information</h2>
      <div class="wide_table">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped">
      <thead>
        <tr>
        <th>Name</th>
        <th>Address</th>
        <th>Date of Birth</th>
        <?php if ($this->student->Checkboxes->count()) {
            echo '<th>Agreements Made</th>';
} ?>
        </tr>
      </thead>
      <tbody>
        <?php
        echo '<tr>'
        . '<td><div style="min-width: 80px;">' . $this->student->firstLastName()
        . '</div></td>'
        . '<td><div style="min-width: 140px;">' . $this->student->fullAddress()
        . '</div></td>'
        . '<td><div style="min-width: 120px;">' . date('F j, Y', strtotime($this->student->date_of_birth))
        . '</div></td>';
        if ($this->student->Checkboxes->count()) {
            echo self::listAgreements($this->student);
        }
        echo '</tr>';
        ?>
      </tbody>
      </table>
      </div>
        <?php
    }

    private static function listAgreements(A25_Record_Student $student)
    {
        $return = '<td><div>';
        foreach ($student->Checkboxes as $checkbox) {
            $return .= '<p><input type="checkbox" disabled checked> '
            . $checkbox->text . '</p>';
        }
        $return .= '</div></td>';
        return $return;
    }
}
