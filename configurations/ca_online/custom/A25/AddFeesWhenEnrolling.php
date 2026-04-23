<?php
class A25_AddFeesWhenEnrolling extends A25_Default_AddFeesWhenEnrolling
{
	/**
	 * @param A25_Record_LocationAbstract $course
	 * @param $extraFee -- Represents an extra fee on top of the location fee
	 */
	protected function _addLocationFees(A25_Record_Course $course, $extraFee)
	{
        $balance = abs($this->_enroll->Student->getAccountBalance());
		$fee = $course->getSetting('fee') + $extraFee;
        if ($balance > $fee) {
            $fee = $balance;
        }
		$this->_orderRecord->createLineItem(
				A25_Record_OrderItemType::typeId_CourseFee,
				$fee);
	}
    // CA Online doesn't charge more for court ordered
	public function createEnrollmentLineItems ()
	{
        $this->_addLocationFees($this->_enroll->Course, $extraFee);
        $this->_orderRecord->addLateFeeIfNecessary();
	}
}
