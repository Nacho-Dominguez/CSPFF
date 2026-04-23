<?php

class A25_Record_Student extends JosStudent
{
	/**
	 * These properties are required for getStringValues().
	 * These are stupid, as is getStringValues().
	 */
	public $state_name;
	public $license_state_name;
	public $license_status_name;
	public $gender_name;
	public $formatted_date_of_birth;

	const licenseStatus_valid = 1;
	const licenseStatus_suspended = 2;
	const licenseStatus_probation = 3;
	const licenseStatus_canceled = 4;
	const licenseStatus_unlicensed = 5;
	const licenseStatus_drivingPermit = 6;

	/**
	 * This silly property is required for showNotes().
	 * @deprecated
	 */
	public $notes;

	/**
	 * This silly property is required for showEnrollment().
	 * @deprecated
	 */
	public $enrollment;

  public function __construct($table = null, $isNewEntry = false) {
		parent::__construct($table, $isNewEntry);

    $this->hasMutator('zip', 'setZip');
  }

	/**
	 * @param integer $id
	 * @return A25_Record_Student
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_Student')->find($id);
    }

	public static function isUserIdAvailable($userid)
	{
		if (!$userid)
			throw new A25_Exception_IllegalArgument ('user_id');
		$finder = new A25_MosDbFinder('A25_Record_Student', A25_DI::DB());
		$students = $finder->loadRecordsWithForeignKey('userid', $userid);
		if (count($students) == 0)
			return true;
		else
			return false;
	}

	/**
	 * Use this function to check if a student is the correct age on the course
	 * date. $timestamp is usually the course date.
	 *
	 */
	public function checkAgeAtTimestamp($timestamp)
	{
		$birthdateTimestamp = strtotime($this->date_of_birth);
    $timestamp = $this->courseDate($timestamp);
		$daysUntilCourse = (int)(($timestamp-time())/(3600*24));
		$oldAge = new A25_Age(PlatformConfig::maxAge+1,-$daysUntilCourse);
		$youngAge = new A25_Age(PlatformConfig::minAge,-$daysUntilCourse);
		if ($birthdateTimestamp < $oldAge->birthdayTimestamp())
			throw new A25_Exception_DataConstraint(
					'You must be younger than ' . (PlatformConfig::maxAge+1)
					. ' on the course date to enroll.');
		if ($birthdateTimestamp > $youngAge->birthdayTimestamp())
			throw new A25_Exception_DataConstraint(
					'You must be ' . PlatformConfig::minAge
					. ' or older on the course date to enroll.');
		return true;
	}

  protected function setZip($value, $load = true)
	{
    if ($value)
      $this->password = $value;

    $this->_set('zip', $value, $load);
	}

  /*
   * Returns the course date if the course is physical, or the current time if
   * it is online.
   */
  private function courseDate($courseDate)
  {
    $rules = A25_DI::Factory()->BusinessRules();
    return $rules->courseDate($courseDate);
  }

	/**
	 * Checks the student object for data consistency
	 * @author Christiaan van Woudenberg
	 * @version June 20, 2006
	 *
	 * @return boolean
	 */
	function check() {
		// check for valid student name
		if (trim($this->first_name == '')) {
			$this->_error = "Student first name cannot be empty.";
			return false;
		}
		if (trim($this->last_name == '')) {
			$this->_error = "Student last name cannot be empty.";
			return false;
		}

		// check for valid e-mail address
//		if (trim($this->email == '')) {
//			$this->_error = "Student e-mail address cannot be empty.";
//			return false;
//		}

		//check for valid address 1
//		if (trim($this->address_1 == '')) {
//			$this->_error = "Address 1 cannot be empty.";
//			return false;
//		}

		//check for valid city
//		if (trim($this->city == '')) {
//			$this->_error = "City cannot be empty.";
//			return false;
//		}

		//check for valid state
//		if (trim($this->state == '')) {
//			$this->_error = "State cannot be empty.";
//			return false;
//		}

		//check for valid zip
//		if (trim($this->zip == '')) {
//			$this->_error = "Zip Code cannot be empty.";
//			return false;
//		}

		//check for valid home_phone
//		if (trim($this->home_phone == '')) {
//			$this->_error = "Primary Phone cannot be empty.";
//			return false;
//		}


		//check for valid userid
		if (trim($this->userid == '')) {
			$this->_error = "User Id cannot be empty.";
			return false;
		}

		//check for gender
		if (trim($this->gender == '')) {
			$this->_error = "Sex cannot be empty.";
			return false;
		}
        
        //license state cannot be blank unless unlicensed
		if ((int) $this->license_status != self::licenseStatus_unlicensed && empty($this->license_state)) {
            $this->_error = "License state cannot be empty.";
			return false;
		}

		//Update fields for unlicensed status
		if ((int) $this->license_status == self::licenseStatus_unlicensed) {
			$this->license_state = 0;
		}

		return self::fireDuringCheck($this);
	}

  public function updateCalculatedValues()
  {
    $this->updateCalculatedValue('calc_balance', 'getAccountBalance');
    $this->updateCalculatedValue('calc_last_payment_date', 'lastPaymentDate');
  }

	/**
	 * Fetch notes for the current student
	 * @author Christiaan van Woudenberg
	 * @version August 1, 2006
	 *
	 * @return null
	 */
	function getNotes( ) {
		if ((int) $this->student_id == 0) {
			return;
		}
		$sql = "SELECT n.*,u.`name` AS created_by FROM #__student_note n"
			. "\n LEFT JOIN #__users u ON (n.`created_by`=u.`id`)"
			. "\n WHERE n.`student_id`=" . (int) $this->student_id;
		A25_DI::DB()->setQuery( $sql );
		$this->notes = A25_DI::DB()->loadObjectList();
	}


	/**
	 * Show a table with student notes
	 * @author Christiaan van Woudenberg
	 * @version August 1, 2006
	 *
	 * @return void
	 */
	function showNotes( )
	{
		if (@!$this->notes) {
			$this->getNotes();
		}

		$str = '';

		// build list of status
		$str = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="striped"><tbody>';
		if (!count($this->notes)) {
			$str .= '<tbody><tr><td>No notes exist for this student.</td></tr></tbody>';
		} else {
			$str .= '<thead><tr><td>Created</td><td>Created By</td><td>Note</td></tr></thead>';
			$str .= '<tbody>';
			foreach ($this->notes as $n) {
				$str .= '<tr>'
					. '<td valign="top">' . $n->created . '</td>'
					. '<td valign="top">' . $n->created_by . '</td>'
					. '<td valign="top">' . nl2br($n->note) . '</td>'
					. '</tr>' . "\n";
			}
			$str .= '</tbody>';
		}
		$str .= '</table>';
		return $str;
	}


	/**
	 * Populate string values of other table information
	 *
	 * This function should be obliterated from existence!  But first, all uses
     * of it need to be removed.
	 *
	 * @author Christiaan van Woudenberg
	 * @version July 27, 2006
	 *
     * @deprecated
     *
	 * @return null
	 */
	function getStringValues( ) {
		if ((int) $this->student_id == 0) {
			return;
		}
		$sql = "SELECT us.`state_name`,lus.`state_name` AS `license_state_name`"
			. "\n , ls.`status_name` AS `license_status_name`"
			. "\n , IF(s.`gender`='M','Male',IF(s.`gender`='F','Female','')) AS `gender_name`"
			. "\n, DATE_FORMAT(s.`date_of_birth`,\"%m/%d/%Y\") AS `formatted_date_of_birth`"
			. "\n FROM #__student s"
			. "\n LEFT JOIN #__us_state us ON (s.`state`=us.`state_code`)"
			. "\n LEFT JOIN #__us_state lus ON (s.`license_state`=lus.`state_code`)"
			. "\n LEFT JOIN #__license_status ls ON (s.`license_status`=ls.`status_id`)"
			//. "\n LEFT JOIN #__hear_about_type h ON (s.`hear_about_id`=h.`hear_about_id`)"
			//. "\n LEFT JOIN #__reason_type r ON (s.`reason_id`=r.`reason_id`)"
			//. "\n LEFT JOIN #__court c ON (s.`referring_court`=c.`court_id`)"
			. "\n WHERE s.student_id='" . (int) $this->student_id . "'"
			. "\n LIMIT 1"
			;
		A25_DI::DB()->setQuery( $sql );
		$row = null;
		A25_DI::DB()->loadObject( $row );
		echo A25_DI::DB()->_errorMsg;
		foreach ($row as $key => $val) {
			$this->$key = $val;
		}
	}

	/**
     *
     * @return array of A25_Record_StudentMessage
     */
	public function getMessages()
	{
		$finder = new A25_MosDbFinder(
				'A25_Record_StudentMessage', A25_DI::DB());
		$messages = $finder->loadRecordsWithForeignKey('student_id',
													   $this->student_id);
		return $messages;
    }

	/**
	 * @return A25_Record_Enroll
   *
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
	 */
	public function getActiveEnrollment()
	{
		$enrollments = $this->Enrollments;
		$active = null;
		foreach($enrollments as $enrollment)
		{
			if($enrollment->isActive())
				$active = $enrollment;
		}
		return $active;
	}

	private function getOrders()
	{
		$enrollments = $this->Enrollments;
		$orders = array();
		foreach ($enrollments as $enrollment) {
			//if ($enrollment->Order->order_id > 0)
				$orders[] = $enrollment->Order;
		}
		return $orders;
  }

  protected function getPayments()
  {
    return $this->Payments;
  }

	public function getAccountBalance()
	{
		$order_total = $this->getNoncanceledOrdersTotal();
		$pay_total = $this->getPaymentsTotal();
		return $order_total - $pay_total;
	}

	public function addLateFeeToAccountBalance($balance)
	{
		if ($balance > 0) {
			$enroll = $this->getActiveEnrollment();
			if ($enroll != null) {
				$order = $enroll->Order;
				if($order->addLateFeeIfNecessary())
					$balance += $enroll->getLateFee();
			}
		}
		return $balance;
	}

	/**
     * @return double
     */
	private function getNoncanceledOrdersTotal ()
	{
		$total = 0;
    foreach ($this->getFees() as $lineItem) {
      if ($lineItem->isActive()) {
        $total += $lineItem->chargeAmount();
      }
    }
		return $total;
	}

  public function getFees()
  {
    $fees = array();
		foreach ($this->getOrders() as $order) {
			foreach ($order->OrderItems as $lineItem) {
        $fees[] = $lineItem;
			}
    }
    return $fees;
  }

	/**
     * @return double
     */
	public function getPaymentsTotal()
	{
		$payments = $this->getPayments();
		$total = 0;
		foreach ($payments as $payment)
			$total += $payment->amount;
		return $total;
	}

	public function enrollInCourse(A25_Record_Course $course,
			$hear_about_id, $reason_id, $is_late=false, $court_id=null,
			$reason_other=null)
	{
		if($hear_about_id < 1)
			throw new A25_Exception_InvalidEntry(
					'You must select "How did you hear about us"');
		if($reason_id < 1)
			throw new A25_Exception_InvalidEntry(
					'You must select a reason for enrollment');
		if(in_array($reason_id, A25_Record_ReasonType::legalMatterList()) &&
				$court_id < 1)
			throw new A25_Exception_InvalidEntry(
					'Please select a Court');
		if($reason_id == A25_Record_ReasonType::reasonTypeId_Other && $reason_other == null )
			throw new A25_Exception_InvalidEntry(
					'Please describe your "other reason" for enrollment');

		$enrollment = new A25_Record_Enroll();
		$enrollment->assignCourse($course);
		$enrollment->hear_about_id = $hear_about_id;
		$enrollment->reason_id = $reason_id;
		$enrollment->is_late = $is_late;
        $enrollment->set('court_id', $court_id);
		$enrollment->status_id = A25_Record_Enroll::statusId_registered;
		$enrollment->date_registered = date( 'Y-m-d H:i:s' );
		$enrollment->reason_other = $reason_other;
    if (strtotime($course->course_start_date) < strtotime(
        $course->getSetting('register_cc_days') . ' days'))
      $enrollment->sent_payment_reminder = 2;

		$this->Enrollments[] = $enrollment;

		$order = new A25_Record_Order();
		$order->insertOrder($enrollment);

		return $enrollment;
	}
	/**
	 * Also saves this student.
	 */
	public function updateOrdersAndEnrollmentsAfterPayment()
	{
		$this->markAppropriateOrdersAndLineItemsAsPaid();

		$this->updateStatusForAllPaidEnrollments();

		$this->save();
	}

	public function markAppropriateOrdersAndLineItemsAsPaid()
	{
    $this->markAppropriateLineItemsAsPaid();
    $this->setOrderPaymentStatusBasedOnItsFeePaymentStatuses();
	}

  protected function markAppropriateLineItemsAsPaid()
  {
		$items = $this->getFees();

		// Loop through the order items, marking as paid/unpaid
		$balance = $this->getAccountBalance();
		for ($i = count($items) - 1; $i >= 0; $i--)
		{
			if ($items[$i]->waived())
				continue;

			if (!$items[$i]->isActive())
				continue;

			if ($balance > 0) {
				$items[$i]->date_paid = null;
				$balance -= $items[$i]->chargeAmount();
			} else {
				if (!$items[$i]->date_paid)
					$items[$i]->markPaid();
			}
		}
  }

  private function setOrderPaymentStatusBasedOnItsFeePaymentStatuses()
  {
		// Loop through the orders, marking as paid/unpaid
		foreach ($this->getOrders() as $order) {
			$pay_status = A25_Record_Order::payStatus_paid;
			foreach ($order->OrderItems as $item) {
				if (!$item->isPaid()) {
					$pay_status = A25_Record_Order::payStatus_unpaid;
					break;
				}
			}
			$order->pay_status_id = $pay_status;
		}
  }

	private function updateStatusForAllPaidEnrollments()
	{
		foreach ($this->getOrders() as $order) {
			if ($order->isPaid())
				$order->Enrollment->updateStatusAfterPayment();
		}
	}
	public function getPreviousEnrollment()
	{
		$enrollments = $this->Enrollments;
		$last = count($enrollments) - 1;
		$newestEnrollment = $enrollments[$last];
		if ($newestEnrollment && $newestEnrollment->isActive())
			if ($enrollments[$last-1]->xref_id > 0)
				return $enrollments[$last-1];
			else
				return null;
		return $newestEnrollment;
	}
  public function getNewestEnrollment()
  {
    if ($this->Enrollments->count() == 0)
      return null;

		$enrollments = $this->Enrollments;
		$last = count($enrollments) - 1;
		return $enrollments[$last];
  }
    public function getCurrentEnrollments()
    {
        if ($this->Enrollments->count() == 0)
            return null;
        $return = array();
        foreach($this->Enrollments as $enrollment) {
            if($enrollment->status_id == 1 || $enrollment->status_id == 2) {
                $return[] = $enrollment;
            }
        }
        return $return;
    }
	/**
	 * If $this->Enrollments[] is not up-to-date, it may not work as expected.
	 *
	 * @param A25_Record_Enroll $enroll
	 * @return A25_Record_Enroll
	 */
	public function getEnrollmentBefore(A25_Record_Enroll $enroll)
	{
		foreach ($this->Enrollments as $next) {
			if ($next->xref_id == $enroll->xref_id)
				return $last;
			$last = $next;
		}
	}
	public function age($when=null)
	{
		if (!$when)
			$when = time();

		$dob_time = strtotime($this->date_of_birth);

		$dob_year = strftime('%Y', $dob_time);
		$when_year = strftime('%Y', $when);
		$age = $when_year - $dob_year;

		$dob_month = strftime('%m', $dob_time);
		$when_month = strftime('%m', $when);
		$dob_day = strftime('%d', $dob_time);
		$when_day = strftime('%d', $when);

		if ($dob_month > $when_month)
			$age -= 1;

		if ($dob_month == $when_month && $dob_day > $when_day)
			$age -= 1;

		return $age;
	}

	/**
	 * This function returns true if the student currently has a driver's
	 * license, or if they have had one before.
	 */
	public function alreadyBeenLicensedOrHasDrivingPermit()
	{
		return in_array($this->license_status,
			array(self::licenseStatus_valid, self::licenseStatus_canceled,
				self::licenseStatus_probation, self::licenseStatus_suspended,
				self::licenseStatus_drivingPermit)
		);
	}

  public function lastPaymentDate()
  {
    $date = null;
    foreach($this->getPayments() as $payment) {
      if ($payment->created > $date)
        $date = $payment->created;
    }
    return $date;
  }

  public function firstLastName()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  public function fullAddress()
  {
    $address = $this->address_1 . '<br />';
    if (strlen($this->address_2)) {
      $address .= $this->address_2 . '<br />';
    }
    $address .= $this->city . ', ' . $this->state . ' ' . $this->zip;
    return $address;
  }

  public function createCheckboxIfNecessary($text)
  {
    foreach ($this->Checkboxes as $checkbox) {
      if ($checkbox->text == $text) {
        return $checkbox;
      }
    }
    $checkbox = new Checkbox();
    $checkbox->Student = $this;
    $checkbox->text = $text;
    return $checkbox;
  }
  
    private function accent2ascii($str) {
    $str = htmlentities($str, ENT_NOQUOTES, 'ISO-8859-1');
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\\1', $str); // For ligatures like '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // Remove any remaining entities
    return $str;
}
  
    private function removeForeignLetters($str) {
        return ucwords(strtolower($this->accent2ascii($str)));
    }

    public function save()
    {
        $this->first_name = $this->removeForeignLetters($this->first_name);
        $this->middle_initial = $this->removeForeignLetters($this->middle_initial);
        $this->last_name = $this->removeForeignLetters($this->last_name);
        $this->address_1 = $this->removeForeignLetters($this->address_1);
        $this->address_2 = $this->removeForeignLetters($this->address_2);
        $this->city = $this->removeForeignLetters($this->city);
        $this->state = strtoupper($this->state);
        self::fireDuringSave($this);
        parent::save();
    }

    private static function fireDuringSave($student)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_LicenseNo) {
                $listener->capitalizeLicenseNumber($student);
            }
        }
    }

    private static function fireDuringCheck($student)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_LicenseInfo) {
                $return = $listener->validateLicenseInfo($student);
                if ($return == false) {
                    return false;
                }
            }
        }
        return true;
    }
}
