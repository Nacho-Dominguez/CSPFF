<?php

class A25_Default_AddFeesWhenEnrolling
{
	protected $_enroll;
	protected $_orderRecord;

	function __construct (A25_Record_Enroll $enroll,
			A25_Record_Order $orderRecord)
	{
		$this->_enroll = $enroll;
		$this->_orderRecord = $orderRecord;
	}

	/**
	 * This function is meant to replace A25_Record_Order::getCourseCost()
	 */
	public function createEnrollmentLineItems ()
	{
		if ((int) $this->_enroll->court_id > 0 || $this->_enroll->isLegalMatter()) {
			$this->_addCourtFees($extraFee);
    } else {
      $this->_addLocationFees($this->_enroll->Course, $extraFee);
    }
    $this->addVirtualCourseFee($this->_enroll->Location);
    $this->_orderRecord->addLateFeeIfNecessary();
	}

	/**
	 * This is a helper function for createEnrollmentLineItems()
	 *
	 * @param $extraFee -- Represents an extra fee on top of the location fee
	 */
	private function _addCourtFees($extraFee)
	{
		$this->addCourtTuition($extraFee);

		// SURCHARGE_LOGIC (mark all surchage logic with this tag so that we can
		// see opportunities to separate it out)
		$this->addCourtSurcharge();
	}

	private function addCourtTuition($extraFee)
	{
		// Calculate court fee:
		if ($this->_enroll->court_id > 0) {
			$court = $this->_enroll->Court;
            $override = self::fireDuringAddCourtTuition($this->_enroll);
            if ($override) {
                $courtfee = $override;
            } else if (!empty($court->fee)) {
				$courtfee = $court->fee;
			} else {
				$courtfee = PlatformConfig::defaultCourtFee;
			}
		} else {
			$courtfee = PlatformConfig::defaultCourtFee;
		}
		$courtfee += $extraFee;

		// Save court fee:
		$this->_orderRecord->createLineItem(
				A25_Record_OrderItemType::typeId_CourseFee, $courtfee);
	}

	protected function addCourtSurcharge()
	{
		$surchargeFee = $this->surchargeAmount();

		if ($surchargeFee > 0 && !$this->alreadyHasSurcharge())
			$this->_orderRecord->createLineItem(
					A25_Record_OrderItemType::typeId_CourtSurcharge,
					$surchargeFee);
	}

  protected function surchargeAmount()
  {
    if (!$this->_enroll->relatedIsDefined('Court') || !$this->_enroll->isCourtOrdered())
      return 0;

    return $this->_enroll->Court->getSurchargeFee();
  }

  protected function alreadyHasSurcharge()
  {
    $current = $this->_enroll;

    while ($current = $current->previousEnrollment()) {
      if ($current->isComplete())
        return false;

      $surcharge = $current->surchargeLineItem();
      if ($surcharge && $surcharge->isActive())
        return true;
    }

    return false;
  }
  
    private function addVirtualCourseFee(A25_Record_Location $location) {
        if ($location->virtual) {
            $fee = A25_DI::PlatformConfig()->virtualCourseFee;
            if ($fee > 0) {
                $this->_orderRecord->createLineItem(
                        A25_Record_OrderItemType::typeId_VirtualCourseFee,
                        $fee);
            }
        }
    }

	/**
	 * @param $extraFee -- Represents an extra fee on top of the location fee
	 */
	protected function _addLocationFees(A25_Record_Course $course, $extraFee)
	{
		$fee = $course->getSetting('fee') + $extraFee;
		$this->_orderRecord->createLineItem(
				A25_Record_OrderItemType::typeId_CourseFee,
				$fee);
	}

    private static function fireDuringAddCourtTuition($enroll)
    {
        foreach (A25_ListenerManager::all() as $listener) {
            if ($listener instanceof A25_ListenerI_CourtFee) {
                return $listener->duringAddCourtTuition($enroll);
            }
        }
        return false;
    }
}
