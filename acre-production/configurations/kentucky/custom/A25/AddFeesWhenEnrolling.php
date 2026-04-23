<?php
class A25_AddFeesWhenEnrolling extends A25_Default_AddFeesWhenEnrolling
{
  /**
   * @todo-jon-high-large - make the driving permit discount into a plugin
   */

	/**
	 * @param A25_Record_LocationAbstract $course
	 * @param $extraFee -- Represents an extra fee on top of the location fee
	 */
	protected function _addLocationFees(A25_Record_Course $course, $extraFee)
	{
		return A25_DrivingPermitDiscount::tuitionFee($this->_enroll,
				$this->_orderRecord, $course, $extraFee);
	}
}
