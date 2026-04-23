<?php

class A25_Record_Order extends JosOrder
{
	const payStatus_unpaid = 1;
	const payStatus_paid = 2;

	/**
	 * @param integer $id
	 * @return A25_Record_Order
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve( $id)
	{
		return Doctrine::getTable('A25_Record_Order')->find($id);
	}

	public function id()
	{
		return $this->order_id;
	}

	public static function paid($alias)
	{
		return "$alias.pay_status_id = " . self::payStatus_paid;
	}

	/**
	 * Checks object for consistency
	 * @author Christiaan van Woudenberg
	 * @version August 3, 2006
	 *
	 * @return boolean
	 */
	function check() {
		// check for valid order name
		if ((int) $this->xref_id == 0) {
			$this->_error = "Enrollment ID cannot be empty.";
			return false;
		}
		return true;
	}

  public function totalAmount()
  {
    $items = $this->OrderItems;
		$total_amount = 0;
		foreach ($items as $item) {
			$total_amount += $item->chargeAmount();
		}
    return $total_amount;
  }

	/**
	 * @return bool
   *
   * @todo-active_enroll If an enrollment is "active", it really means that the
   * student would owe tuition.  Verify that no usages of it assume a different
   * meaning, then use a better word than "Active" in all of the methods that
   * are related to Enrollments.
   */
	public function isActive()
	{
    if($this->relatedIsDefined('Enrollment'))
      if($this->Enrollment->isActive())
        return true;

		return false;
  }

	/**
	 * @return bool
	 */
	public function isPaid()
	{
    return $this->pay_status_id == self::payStatus_paid;
  }

	/**
	 * Figures out order price, and inserts it into the database.
	 *
	 * @param A25_Record_Enroll $enroll
	 * @author Thomas Albright
	 */
	public function insertOrder($enroll) {
		$this->Enrollment = $enroll;

		$feeAssigner = new A25_AddFeesWhenEnrolling($enroll,$this);
		$feeAssigner->createEnrollmentLineItems();
	}

	public function createLineItem($orderItemTypeId, $amount)
	{
    $feeClass = A25_Record_OrderItem::getSubclass($orderItemTypeId);
		$itemRecord = new $feeClass();
		$this->OrderItems[] = $itemRecord;
    $itemRecord->type_id = $orderItemTypeId;
    $itemRecord->quantity = 1;
    $itemRecord->unit_price = $amount;
		return $itemRecord;
	}

	public function A25_AddFeesWhenMarkingAsNoShow()
	{
        // Make sure two no-show fees aren't added to same enrollment
        if ($this->getNonrefundableBecauseOfNoShowsItem()) {
            return;
        }
        
        $fee = 0;
        if (A25_DI::PlatformConfig()->noShowFeeIsCourseFee === true) {
            $lineItem = $this->getCourseFeeLineItem();
            if ($lineItem) {
                $fee = $lineItem->unit_price;
            }
		} else if ($this->Enrollment->reason_id == A25_DI::PlatformConfig()->noShowDiscountReason) {
            $fee = A25_DI::PlatformConfig()->noShowDiscountedFee;
        } else {
            $fee = A25_DI::PlatformConfig()->noShowFee;
        }
        $this->createLineItem(
                A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows,
                $fee);
	}
	/**
	 * @return A25_Record_OrderItem
	 */
	private function getCourseFeeLineItem()
	{
		return $this->getLineItemOftype(
				A25_Record_OrderItemType::typeId_CourseFee);
	}

	/**
	 * @return A25_Record_OrderItem
	 */
	public function getNonrefundableBecauseOfNoShowsItem()
	{
		return $this->getLineItemOftype(
				A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows);
	}

	/**
	 * @return A25_Record_OrderItem
	 */
	public function getSurchargeLineItem()
	{
		return $this->getLineItemOftype(
				A25_Record_OrderItemType::typeId_CourtSurcharge);
	}

	/**
	 * @return A25_Record_OrderItem
	 */
	public function getLateFeeLineItem()
	{
		return $this->getLineItemOftype(
				A25_Record_OrderItemType::typeId_LateFee);
	}

	/**
	 * @return A25_Record_OrderItem
	 */
	public function getLineItemOfType($type)
	{
		$items = $this->OrderItems;
		foreach($items as $item)
			if ($item->type_id == $type)
				return $item;
	}
	private function getLineItemAmountOfType($type)
	{
		$item = $this->getLineItemOfType(
				$type);
		if ($item != null)
			return $item->chargeAmount();
		else
			return 0;
	}
	public function tuitionFee()
	{
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemType::typeId_CourseFee);
	}
	public function courtSurchargeAmount()
	{
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemType::typeId_CourtSurcharge);
	}
	public function lateFee()
	{
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_LateFee);
	}
  public function replaceCertFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_ReplaceCertFee);
  }
  public function returnCheckFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_ReturnCheckFee);
  }
  public function noShowFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_NonrefundableBecauseOfNoShows);
  }
  public function creditCardFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_CreditCardFee);
  }
  public function donationFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_Donation);
  }
  public function virtualCourseFee()
  {
		return $this->getLineItemAmountOfType(
				A25_Record_OrderItemtype::typeId_VirtualCourseFee);
  }
	/**
	 *
	 * @return bool - true if fee was added
	 */
	public function addLateFeeIfNecessary()
	{
		if (!$this->isPaid() && $this->getCourse() != null &&
        $this->getCourse()->isPastLateFeeDeadline()
            // Don't add another late fee if it's already been waived
            && !$this->hasFeeOfTypeEvenIfWaived(A25_Record_OrderItemType::typeId_LateFee)) {
			$this->addLateFee();
			return true;
		}
		return false;
	}
	/**
	 * @return bool
	 */
	public function hasLateFee()
	{
		return $this->hasFeeOfType(A25_Record_OrderItemType::typeId_LateFee);
	}
	/**
	 * @return bool
	 */
	public function hasSurchargeFee()
	{
		return $this->hasFeeOfType(A25_Record_OrderItemType::typeId_CourtSurcharge);
	}
	/**
	 * protected for testing
	 * @return bool
	 */
	public function hasFeeOfType($type)
	{
		foreach ($this->OrderItems as $item) {
			if ($item->type_id == $type && $item->isActive()) {
				return true;
			}
		}
		return false;
	}
	public function hasFeeOfTypeEvenIfWaived($type)
	{
		foreach ($this->OrderItems as $item) {
			if ($item->type_id == $type) {
				return true;
			}
		}
		return false;
	}
	public function addLateFee()
	{
		$this->createLineItem(A25_Record_OrderItemType::typeId_LateFee,
					$this->Enrollment->getLateFee());
	}

  public function courseDatetime()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->courseDatetime();
  }

  public function wasAttended()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->wasAttended();
  }

  public function getCourse()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->Course;
  }

  public function getCourt()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->Court;
  }

  public function getStudent()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->Student;
  }

  public function getStatusId()
  {
    if ($this->relatedIsDefined('Enrollment'))
      return $this->Enrollment->status_id;
  }
}
