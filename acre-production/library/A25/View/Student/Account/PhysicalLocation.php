<?php

class A25_View_Student_Account_PhysicalLocation extends A25_View_Student_Account
{
  protected function enrollInACourse()
  {
    ?>
    You are not currently enrolled in any upcoming courses. To view past courses
    you took, see the "Enrollment History" section below.  To enroll in a
    course, <a href="<?php echo PlatformConfig::findACourseUrl()?>">click here</a>.
    <?php
  }

    protected function courseInfo()
    {
        if(A25_DI::PlatformConfig()->onlyOneEnrollmentAllowed) {
            if ($this->newest_enrollment && $this->newest_enrollment->isActive()
                && !$this->newest_enrollment->hasBeenAttended()) {
              $this->upcomingCourseMessage($this->newest_enrollment);
            }
            else if ($this->newest_enrollment
                && $this->newest_enrollment->status_id == A25_Record_Enroll::statusId_kickedOut
                && !$this->newest_enrollment->courseIsPast()) {
              echo $this->kickedOutMessage();
            } else {
              $this->enrollInACourse();
            }
        } else if ($this->current_enrollments) {
            foreach($this->current_enrollments as $enroll) {
                  $this->upcomingCourseMessage($enroll);
            }?>
    <a class="action_link" style="margin-top: 5px;" href="<?php echo PlatformConfig::findACourseUrl(); ?>">
      Click here enroll in another course</a><?php
        } else if ($this->newest_enrollment
            && $this->newest_enrollment->status_id == A25_Record_Enroll::statusId_kickedOut
            && !$this->newest_enrollment->courseIsPast()) {
          echo $this->kickedOutMessage();
        } else {
          $this->enrollInACourse();
        }
    }

  protected function upcomingCourseMessage($enroll)
  {
    $course = $enroll->Course;

    echo A25_Html::courseMessage($course); 
    echo A25_Html::certificateMessage($course, $this->student)?>
    <div style="font-size: 10px; color: #999;">
      <p>
      <?php if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo A25_DI::PlatformConfig()->cancellationPolicyForEmailSpanish();
        }
        else {
            echo A25_DI::PlatformConfig()->cancellationPolicyForEmail();
        }
      if (A25_DI::PlatformConfig()->chargesForCourse()) {
      echo A25_DI::PlatformConfig()->cancellationTextOnAccountPage($course); } ?>
      </p>
    </div>
    <a class="action_link" href="<?php echo $this->cancellationLinkLocation($enroll) . '">';
    if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
        echo 'Haz clic aqu&iacute; para cancelar esta inscripci&oacute;n';
    }
    else {
        echo 'Click here to cancel this enrollment';
    }
    echo '</a>';
  }

  protected function reservationMessage()
  {
    if ($this->newest_enrollment
        && $this->newest_enrollment->status_id == A25_Record_Enroll::statusId_registered
        && $this->newest_enrollment->kick_out_date != null) {
      $paymentTimer = new A25_PaymentTimer($this->newest_enrollment);
      $return = '<div style="float: left; max-width: 325px; margin-bottom: 12px;">';
      $return .= $paymentTimer->insert();
      $return .= 'We have reserved a seat for you in this class. However'
          . ', if payment is not received ' . $this->paymentDeadline()
          . ', you will lose your reservation.</div>';
      return $return;
    }
    elseif($this->newest_enrollment && $this->newest_enrollment->isActive()) {
        if ($this->newest_enrollment->Course->course_type_id == A25_Record_Course::typeId_Spanish) {
            return 'Est&aacute;s inscrito en la siguiente clase:';
        }
        else {
            return 'You are enrolled in the following class:';
        }
    }
    else
      return '';
  }

  protected function enrollmentHistory()
  {
    if (!$this->newest_enrollment)
      return;
    ?>
		<h2 style="clear: both;">Enrollment History</h2>
		<p>
		<?php
    $balance = $this->student->getAccountBalance();
    echo '<b>Account Balance: </b>';
		if($balance < 0) {
      echo 'You have a credit of ';
    } else if ($balance == 0) {
      // No special message
		} else {
      echo 'You owe ';
		}
    echo '$' . number_format(abs($balance),2);
		?>
    </p>
    <div class="wide_table">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped">
		<thead>
			<tr>
				<th>Status</th>
				<th>Location</th>
				<th>Date/Time</th>
				<th></th>
			</tr>
		</thead>
		<tbody>

		<?php
		$enrollments = $this->student->Enrollments;
		foreach ($enrollments as $enrollment) {
		    //show the Cancellation link if the student can cancel the course
		    $cancellation = '';
		    if($enrollment->statusDescriptor()->isInactive() || $enrollment->courseIsPast()) {
		        $cancellation = '';
		    } elseif(!$enrollment->Course->isPastCancellationDeadline()){
		        $cancellation = '<a href="' . $this->cancellationLinkLocation($enrollment) . '">Cancel Enrollment</a>';
		    } else {
		        $cancellation = "Cancellation deadline has past.";
		    }
			echo '<tr>'
				. '<td>' . $enrollment->Status->status_name . '</td>'
				. '<td>' . $enrollment->Course->showCourseInfo('simple') . '</td>'
				. '<td>' . $enrollment->Course->formattedDate('course_start_date') . '</td>'
				. '<td>' . $cancellation . '</td>'
				. '</tr>'
				;
		}

		if (!count($enrollments)) {
			echo '<tr><td colspan="5">You are not currently enrolled for any courses.</tr>';
		}
		?>
		</tbody>
		</table>
    </div>
    <?php
  }

  public function kickOutIfNecessary()
  {
    if (!$this->newest_enrollment)
      return;
    if (!$this->newest_enrollment->kick_out_date)
      return;

    /**
     * @todo-jon-small-low - change this to use Enroll->courseIsPast() instead.
     * This will require changes to the unit test, too.
     */
    if (!$this->newest_enrollment->Course->isPast() &&
        in_array($this->newest_enrollment->status_id, A25_Record_Enroll::reservationIsTemporaryStatusList()) &&
        strtotime($this->newest_enrollment->kick_out_date) < time())
    {
      $kickOut = A25_DI::Factory()->KickOut();
      $kickOut->sendToIndividual($this->newest_enrollment);
    }
  }

  protected function paymentNotes()
  {
    $generator2 = new A25_Remind_HtmlBodyGenerator();
    if (!$this->newest_enrollment->kick_out_date
        || strtotime($this->newest_enrollment->kick_out_date) > strtotime(A25_DI::PlatformConfig()->kickOutAfterDeadline)) {
      echo $generator2->slowPaymentInstructions($this->student, $this->newest_enrollment->Course, $this->newest_enrollment);
    } else {
      echo $generator2->cancelPolicy($this->newest_enrollment->Course);
      echo $this->lateFeeFootnote();
      echo $this->surchargeFootnote();
    }
  }
}
