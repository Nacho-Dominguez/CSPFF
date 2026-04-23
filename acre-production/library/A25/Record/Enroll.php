<?php

/**
 * This class has the extra property location_id, which is not needed because
 * Course is related to location.  Someday we should remove it from the
 * database.  Never query on location_id, but do save it.
 *
 * Never set location_id or course_id directly.  Use assignCourse() instead.
 */
class A25_Record_Enroll extends JosStudentCourseXref
{
	const statusId_registered = 1;
	const statusId_student = 2;
	const statusId_completed = 3;
	const statusId_canceled = 4;
	const statusId_noShow = 5;

  /**
   * We're not exactly sure what 'unavailable' meant, but there hasn't been
   * any students given this status since 2006.  It's best to just count it as
   * attended for accounting purposes.
   */
	const statusId_unavailable = 6;

  /**
   * "Pending" has at least 3 different potential meanings:
   * - Completed the course, but has not paid yet
   * - Completed and paid, but has not received their certificate yet
   * - The course will be paid for by an agency afterwards, so the student has
   *   completed and received their certificate, but has an account balance of $0
   *   for the moment because they are not responsible for paying themselves.  But
   *   the agency still needs to pay for them.
   */
	const statusId_pending = 7;
	const statusId_failed = 8;
  const statusId_kickedOut = 9;

  /**
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
  static public function inactiveStatusList()
  {
    return self::statusList('isInactive');
  }

  static private function statusList($indicator_method)
  {
    $list = array();
    foreach (A25_EnrollmentStatus::all() as $status) {
      if ($status->$indicator_method())
        $list[] = $status->statusId();
    }
    return $list;

  }

  static public function occupiesSeatStatusList()
  {
    return self::statusList('occupiesSeat');
  }

  static public function attendedStatusList()
  {
    return self::statusList('wasAttended');
  }

  static public function canCountAsPaidStatusList()
  {
    return self::statusList('canCountAsPaid');
  }

  static public function reservationIsTemporaryStatusList()
  {
    return self::statusList('reservationIsTemporary');
  }

  static public function isCompleteStatusList()
  {
    return self::statusList('isComplete');
  }

  static public function blocksOtherEnrollmentsStatusList()
  {
    return self::statusList('blocksOtherEnrollments');
  }

  /**
   * @return A25_EnrollmentStatus
   */
  public function statusDescriptor()
  {
    foreach (A25_EnrollmentStatus::all() as $status) {
      if ($status->statusId() == $this->status_id)
        return $status;
    }
  }

	/**
	 * @param integer $id
	 * @return A25_Record_Enroll
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_Enroll')->find($id);
	}

	public function save()
	{
		$this->setDateCompleted();
    $this->sendCompletionEmailIfNecessary();
		return parent::save();
	}

  public function saveAfterApplyingBusinessRules()
  {
    if ($this->status_id == A25_Record_Enroll::statusId_noShow) {
//        && $this->_oldValues['status_id']
//        && $this->_oldValues['status_id'] != A25_Record_Enroll::statusId_noShow) {
      $this->markAsNoShow();
    }
    // We may not need to call save twice here. If performance becomes an issue
    // we can remove the first one if we test it carefully.
    $this->save();
    $this->removeSurchargeIfUnpaidAndInactive();
    $this->removeLateFeeIfUnpaidAndInactive();
    $this->removeVirtualCourseFeeIfUnpaidAndInactive();
    return $this->save();
  }

	protected function setDateCompleted()
	{
		if ($this->status_id == self::statusId_completed
				&& $this->date_completed == '0000-00-00')
			$this->date_completed = date('Y-m-d',time());
	}

	function check()
  {
		// check for valid student reference
		if ((int) $this->student_id == 0) {
			$this->_error = "Student ID cannot be empty.";
			return false;
		}

		// check for valid location reference
		if ((int) $this->location_id == 0) {
			$this->_error = "There is no location associated with this course";
			return false;
		}

		// check for valid course reference
		if ((int) $this->course_id == 0) {
			$this->_error = "Course ID cannot be empty.";
			return false;
		}

		// check for other reason
		if ((int) $this->reason_id == A25_Record_ReasonType::reasonTypeId_Other && trim($this->reason_other) == '' ) {
			$this->_error = "Please give another reason for attending.";
			return false;
		}

		//check for referring court if reason for attending is court related
		if ((int) $this->isLegalMatter() && ( trim($this->court_id) == '' && trim($this->court_other) == '' ) ) {
			$this->_error = "Please select your referring court.";
			return false;
		}

		// check for status id
		if ((int) $this->status_id == 0) {
			$this->_error = "Status ID cannot be empty.";
			return false;
		}

		// check for valid date registered
		if ((int) $this->date_registered == 0) {
			$this->_error = "Date Registered cannot be empty.";
			return false;
		}

    if ($this->fireAdditionalCheck())
      return false;

		return true;
	}

	/**
	 * @return string
	 */
	protected function infoForEmail() {
		$course = $this->Course;

		$str = A25_Html::courseMessage($course);
		$str .= '<table cellspacing="0" cellpadding="0" border="0" width="100%">';
		$str .= "<tr><td>Phone Number:</td><td>" . $course->Location->phone . "</td></tr>\n";
		$str .= "<tr><td>Contact:</td><td>" . $course->Location->contact . "</td></tr>\n";

		if ($this->Student->special_needs) {
			$str .= "<tr><td>\n\nSpecial Needs:\n</td><td>" . $this->Student->special_needs . "</td></tr>\n";
		}
		$str .= '</table>';

		return $str;
	}
	public function virtualCourseInfo() {
		$course = $this->Course;

        $str =  '<div style="background-color: #f2f2f2; padding: 12px;
        text-align: center; color: #333; max-width: 280px; clear: left;">'
                . $course->formattedDate('course_start_date', 'l, F j, Y')
        . "<br/>" . $course->formattedDate('course_start_date', 'g:i a')
        . " &ndash; " . date('g:i a', strtotime($course->getEndTime())) . "<br/>";
        $str .= "Zoom link: ";
        
        if ($course->zoom_link) {
            $str .= '<a href="' . $course->zoom_link . '">' . $course->zoom_link . '</a>';
        }
        else {
            $str .= "Will be sent out 1 week before class";
        }
        
        $str .= "</div>";

		return $str;
	}

	public function getEnrollmentEmailBody()
	{
		$body = $this->Course->getEnrollmentEmailBody();
		return $this->replaceBodyTags($body);
	}

	public function getEnrollmentEmailSubject()
	{
		return $this->Course->Location->getSetting('enrollment_email_subject');
	}
	/**
	 * Sends the enrollment e-mail to the student.
	 *
	 */
	public function sendEnrollmentEmail() {
    if (!$this->course_id)
      return;
		if($this->Course->isPast())
			return;

		$body = $this->getEnrollmentEmailBody();
		$subject = $this->getEnrollmentEmailSubject();

		$this->sendEmail($subject, $body);
	}
	/**
	 * Sends the special needs e-mail to the instructor.
	 *
	 */
	protected function sendSpecialNeedsEmail()
  {
    if (!$this->course_id)
      return;
		if($this->courseIsPast())
			return;

		$student = $this->Student;
		if (!empty($student->special_needs)) {
			$course = $this->Course;
			$subject = A25_EmailContent::wrapSubject('Notification of special-needs student');
			$body = "A special needs student has enrolled in your class.\n\n" .
					"First name: $student->first_name\n" .
					"Last name: $student->last_name\n" .
					"Special needs: $student->special_needs\n\n" .
					"Course Date: " . $course->prettyDateTime() . "\n" .
					"Course Location: " . $course->getLocationName();

			A25_DI::Mailer()->mail(ServerConfig::specialNeedsEmailAddress(),
					$subject, $body, false);
			if ($course->relatedIsDefined('Instructor')) {
				$instructor = $course->Instructor;
				A25_DI::Mailer()->mail($instructor->email, $subject, $body, false);
			}
		}
	}
	/**
	 * This is public only for testing.
	 */
	public function replaceBodyTags($body)
	{
		$late_fee = $this->getLateFee();

		$body = str_replace("!ENROLLMENT!",
			$this->infoForEmail(), $body);
		$body = str_replace("!FEE!",
			number_format($this->Student->getAccountBalance(),2),
			$body);
		$body = str_replace("!LATE_FEE!", $late_fee,
			$body);

		// SURCHARGE_LOGIC (mark all surchage logic with this tag so that we can
		// see opportunities to separate it out)
		$surchargeFee = 0;
    $surchargeItem = $this->surchargeLineItem();
    if ($surchargeItem)
      $surchargeFee = $surchargeItem->faceValue();

		if ($surchargeFee) {
			if ($surchargeItem->waived())
				$surchargeMessage = '<b>You indicated that your referring court '
					. 'gave you a form to waive the DOR surcharge.  Because '
					. 'you have not been charged the DOR surcharge, '
					. 'the form must be submitted to the Alive at 25 office in order to '
					. 'receive credit for the course.</b>  '
					. PlatformConfig::surchargeFootnote($surchargeItem->unit_price);
			else
				$surchargeMessage = 'This amount includes a $'
					. $surchargeFee . ' DOR surcharge.  '
					. PlatformConfig::surchargeFootnote($surchargeItem->unit_price);
		} else {
			$surchargeMessage = '';
		}

		$body = str_replace("!SURCHARGE!", $surchargeMessage,$body);
		$body = str_replace("!CONTACT!", PlatformConfig::contactUs(),$body);
		$body = str_replace("!ACCOUNT_INFO!", A25_Html::studentAccountInformation($this->Student),$body);
        $body = str_replace("!DAY_BEFORE!", date("l F j", strtotime($this->Course->course_start_date . "-1 day")),$body);
        $body = str_replace("!VIRTUAL_COURSE_INFO!", $this->virtualCourseInfo() ,$body);
		return $body;
	}

  public function surchargeLineItem()
  {
		if ($this->relatedIsDefined('Order'))
			return $this->Order->getSurchargeLineItem();
  }

	/**
	 * Gets the late fee value from the referring court, or just the default
	 * late fee, if the court doesn't have a late fee, or if the student is not
	 * court-referred.
	 *
	 * @return int
	 */
	public function getLateFee()
	{
		if ($this->relatedIsDefined('Course'))
			return $this->Course->getLateFee();
	}

  protected function getCompletionEmailBody()
  {
		$body = $this->Course->getCourseCompletedEmailBody($this->reason_id);
    return $body;
  }

  // Public for testing only
	public function sendCompletionEmail()
	{
		$subject = $this->Course->Location->getSetting('course_completed_email_subject');
    $body = $this->getCompletionEmailBody();

		$this->sendEmail($subject,$body);
    $this->fireSendAdditionalEmail();
	}

  public function sendCompletionEmailIfNecessary()
  {
    $modified = in_array('status_id', $this->_modified);
    if ($modified && $this->status_id == self::statusId_completed)
    {
      $this->sendCompletionEmail();
    }
  }

	private function sendEmail($subject,$body)
	{
		A25_Emailer::emailStudent($this->Student,
			$this->course_id, $subject, $body);
	}

	/**
	 * Assigns Course to this enroll recored
	 */
	public function assignCourse( A25_Record_Course $courseRecord )
	{
		$this->Course = $courseRecord;
		if ($courseRecord->relatedIsDefined('Location'))
			$this->Location = $courseRecord->Location;
	}

	/**
	 * Assigns Court to this enroll recored
	 */
	public function assignCourt( A25_Record_Court $courtRecord )
	{
		$this->Court = $courtRecord;
		$this->reason_id = A25_Record_ReasonType::reasonTypeId_CourtOrdered;
	}

	/**
	 * @deprecated - Enrollment <-> Order needs to be 1-to-1, so getOrder() is
	 * correct, not getOrders().
	 * @return array of A25_Record_Order
	 */
	public function getOrders()
	{
		$finder = new A25_MosDbFinder(
				'A25_Record_Order', A25_DI::DB());
		return $finder->loadRecordsWithForeignKey('xref_id',
			$this->xref_id);
	}

	public function getLocationName()
	{
		if ($this->relatedIsDefined('Course'))
			return $this->Course->getLocationName();
	}

	public function courtName()
	{
		if ($this->relatedIsDefined('Court'))
			return $this->Court->court_name;
	}

	public function hearAboutName()
	{
		if ($this->relatedIsDefined('HearAboutType'))
			return $this->HearAboutType->hear_about_name;
	}

	public function reasonName()
	{
		if ($this->relatedIsDefined('ReasonType'))
			return $this->ReasonType->reason_name;
	}

	public function courseDatetime()
	{
		if ($this->relatedIsDefined('Course'))
			return $this->Course->course_start_date;
	}

	public function statusName()
	{
		if ($this->relatedIsDefined('Status'))
			return $this->Status->status_name;
	}

  public function courseIsPast()
  {
		if ($this->relatedIsDefined('Course'))
			return $this->Course->isPast();
    return false;
  }

	/**
	 * Cancels an existing enrollment.  If there is an order, it is cancelled.
	 *
	 * @global string $mosConfig_absolute_path
	 * @global <type> $mosConfig_offset
	 * @return string - message of success or failure
	 */
	function cancelEnrollment()
	{
		//first check to make sure this is an existing enrollment; return an error if it isn't.
		if(!$this->course_id) {
			return "You are not currently enrolled for the course you attempted to cancel.";
		}

		$this->cancel();

		$msg = 'The enrollment has been cancelled.';

		return $msg;
	}
	public function cancel()
	{
		if ($this->status_id == self::statusId_canceled)
			return;

		$this->status_id = self::statusId_canceled;
		$this->saveAfterApplyingBusinessRules();
	}

  /**
   * @todo-jon-low-small - unit test and remove duplication with cancel().
   * (Unit test cancel() too before removing duplication, if it's not already
   * tested somewhere.)
   */
	public function kickOut()
	{
		if ($this->status_id == self::statusId_kickedOut)
			return;

		$this->status_id = self::statusId_kickedOut;
		$this->saveAfterApplyingBusinessRules();
	}
	/**
	 * This really means "Would the student owe for tuition?" If counting seats,
   * use occupiesSeat() instead.
   *
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   *
	 * @return bool
	 */
	public function isActive()
	{
		return !in_array($this->status_id,self::inactiveStatusList());
	}
	public function hasBeenAttended()
	{
    $rules = A25_DI::Factory()->BusinessRules();
    return $rules->hasBeenAttended($this);
	}
  public function occupiesSeat()
  {
    return in_array($this->status_id, self::occupiesSeatStatusList());
  }
  public function isComplete()
  {
    return in_array($this->status_id, self::isCompleteStatusList());
  }
  /**
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
	public static function active($alias)
	{
		return "$alias.status_id NOT IN ("
				. implode(',',self::inactiveStatusList()) . ')';
	}
	public static function elligibleForCourseRevenue($alias)
	{
		return "$alias.status_id NOT IN ("
				. implode(',',PlatformConfig::enrollmentStatusesNotElligibleForCourseRevenue()) . ')';
	}
  public function lineItems()
  {
    if ($this->relatedIsDefined('Order'))
      return $this->Order->OrderItems;
  }
	private function getRefundableLineItems()
	{
		return $this->getLineItemsBasedOnRefundability(true);
	}
	private function getLineItemsBasedOnRefundability($refundable)
	{
		$lineItemsReturned = array();
		$lineItems = $this->lineItems();
		foreach($lineItems as $lineItem)
		{
			if($refundable)
				$decision = !$lineItem->isIndependentOfEnrollment();
			else
				$decision = $lineItem->isIndependentOfEnrollment();
			if($decision)
			{
				$lineItemsReturned[] = $lineItem;
			}
		}
		return $lineItemsReturned;
	}
    private function sendEmailsAfterPayment() {
        $this->sendEnrollmentEmail();
        $this->sendSpecialNeedsEmail();
        $this->fireSendAdditionalEmailAfterPayment();
    }
	public function updateStatusAfterPayment()
	{
    $statusDescriptor = $this->statusDescriptor();
    if ($this->waitingOnWaivedFeeConfirmation()) {
      if ($this->courseIsPast()) {
        if ($statusDescriptor->allowsPaymentEffectsAfterCourse()) {
          $this->status_id = self::statusId_pending;
        }
      } else {
        if ($statusDescriptor->allowsPaymentEffectsBeforeCourse()) {
          if ($statusDescriptor->preEnrollmentEmail()) {
            $this->sendEmailsAfterPayment();
          }
          $this->status_id = self::statusId_pending;
        }
      }
    } else {
      if ($this->courseIsPast()) {
        if ($statusDescriptor->allowsPaymentEffectsAfterCourse()) {
          $this->status_id = self::statusId_completed;
        }
      } else {
        if ($statusDescriptor->allowsPaymentEffectsBeforeCourse()) {
          if ($statusDescriptor->preEnrollmentEmail()) {
            $this->sendEmailsAfterPayment();
          }
          $this->status_id = self::statusId_student;
        }
      }
    }
	}
  protected function waitingOnWaivedFeeConfirmation()
  {
    $lineItems = $this->lineItems();
    if (!$lineItems)
      return false;

    foreach ($lineItems as $fee)
      if ($fee->waivedButUnconfirmed())
        return true;

    return false;
  }
	/**
	 * This code really doesn't do much at the moment, because an Order looks
	 * at its enrollment to see if it is active.  Therefore, an active enrollment
	 * has active orders.  However, it is likely the the business rule
	 * will change.  That is why we made this function.
	 */
	public function getActiveOrders()
	{
		$orders = $this->getOrders();
		$return = array();
		foreach ($orders as $order) {
			if ($order->isActive())
				array_push($return, $order);
		}
		return $return;
	}
	public function markAsNoShow()
	{
		$this->NoShowNonCredit();

		$this->status_id = self::statusId_noShow;
    $this->Student->updateOrdersAndEnrollmentsAfterPayment();
	}

	protected function NoShowNonCredit()
	{
    if (PlatformConfig::noShowsBeforeNoShowFee <= 1) {
      if ($this->isPaid())
        $this->Order->A25_AddFeesWhenMarkingAsNoShow();
      return;
    }

    $current = $this;
    for ($count = 1; $count < PlatformConfig::noShowsBeforeNoShowFee; $count++)
    {
      do {
        $current = $current->previousEnrollment();
      } while($current
          && $current->status_id != self::statusId_completed
          && $current->status_id != self::statusId_noShow
      );
      $previousNoShow = $current;

      if ($previousNoShow->status_id != self::statusId_noShow)
        return;

      // Don't mark 2 No-shows non-refundable in a row:
      if ($previousNoShow->Order->getNonrefundableBecauseOfNoShowsItem() != null)
        return;
    }
    if ($previousNoShow->isPaid())
      $this->Order->A25_AddFeesWhenMarkingAsNoShow();
	}

  /**
   * Although this seems redundant, it is necessary to work with
   * A25_Remind_Students->sendToIndividual().
   *
   * @return A25_Record_Student
   */
  public function getStudent()
  {
    return $this->Student;
  }

  public function previousEnrollment()
  {
    return $this->Student->getEnrollmentBefore($this);
  }

	/**
	 * Enters a refund for all refundable fees associated with this enrollment.
	 */
	public function refund($payTypeId)
	{
		$refund = new A25_Record_Pay();
		$refund->amount = - $this->getRefundableFeeTotal();
		$refund->assignEnrollment($this);
		$refund->pay_type_id = $payTypeId;
		$refund->checkAndStore();
	}
	private function getRefundableFeeTotal()
	{
		$refundableLineItems = $this->getRefundableLineItems();
		// loop through and total
		$total = 0;
		foreach($refundableLineItems as $refundableLineItem)
		{
			$total += $refundableLineItem->chargeAmount();
		}
		return $total;
	}
	public function isCourtOrdered()
	{
		return (in_array($this->reason_id, A25_DI::PlatformConfig()->courtOrderedReasonTypeList));
	}
	public function isLegalMatter()
	{
		return (in_array($this->reason_id, A25_Record_ReasonType::legalMatterList()));
	}
	public function isPaid()
	{
    $items = $this->lineItems();
    foreach ($items as $item) {
      if (!$item->isPaid())
        return false;
    return true;
    }
	}
	public function getReasonForEnrollmentName()
	{
		if ($this->relatedIsDefined('ReasonType'))
			return $this->ReasonType->reason_name;
	}

  public function wasAttended()
  {
    return in_array($this->status_id, self::attendedStatusList());
  }

  /**
   * @todo-jon-medium-small - since removeSurchargeIfUnpaidAndInactive() and
   * removeLateFeeIfUnpaidAndInactive() are always called together, create a
   * wrapper function that does both, and replace all of the other calls to
   * the 2 of them with the wrapper function.  Then, change them both to be
   * protected rather than public, as only the wrapper function needs to be
   * public.
   */
  public function removeSurchargeIfUnpaidAndInactive()
  {
    if ($this->isActive())
      return;

    $surcharge = $this->surchargeLineItem();
    if($surcharge && !$surcharge->isPaid()) {
      $surcharge->delete();
      if ($this->relatedIsDefined('Order'))
        $this->Order->refresh(true);
    }
  }

  public function removeLateFeeIfUnpaidAndInactive()
  {
    if ($this->isActive())
      return;

    $late_fee = $this->getLineItemOfType(A25_Record_OrderItemType::typeId_LateFee);
    if($late_fee && !$late_fee->isPaid()) {
      $late_fee->delete();
      if ($this->relatedIsDefined('Order'))
        $this->Order->refresh(true);
    }
  }

  public function removeVirtualCourseFeeIfUnpaidAndInactive()
  {
    if ($this->isActive())
      return;

    $virtual_fee = $this->getLineItemOfType(A25_Record_OrderItemType::typeId_VirtualCourseFee);
    if($virtual_fee && !$virtual_fee->isPaid()) {
      $virtual_fee->delete();
      if ($this->relatedIsDefined('Order'))
        $this->Order->refresh(true);
    }
  }

  public function hasFeeOfType($type)
  {
    if (!$this->relatedIsDefined('Order'))
      return false;

    return $this->Order->hasFeeOfType($type);
  }
  public function getLineItemOfType($type)
  {
    if (!$this->relatedIsDefined('Order'))
      return false;

    return $this->Order->getLineItemOfType($type);
  }

  private function fireSendAdditionalEmail()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_SendAdditionalEmail)
        $body = $listener->sendAdditionalEmail ($this->xref_id, $this->Student->email);
    }
    return $body;
  }

  private function fireAdditionalCheck()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_AddEnrollCheck)
        $body = $listener->addEnrollCheck($this);
    }
    return $body;
  }

  private function fireSendAdditionalEmailAfterPayment()
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_SendAdditionalEmailAfterPayment)
        $body = $listener->sendAdditionalEmailAfterPayment($this);
    }
    return $body;
  }
}
