<?php
class A25_ListPayOpts extends A25_Default_ListPayOpts
{
	protected function tuitionLabel()
	{
		$return = 'Tuition';
		if (A25_DrivingPermitDiscount::studentGetsDiscount($this->_order->Enrollment))
			$return .= '*';
		return $return;
	}
	public function footer()
	{
		if( !$this->shouldDisplaySurcharge() 
				&& !A25_DrivingPermitDiscount::studentGetsDiscount($this->_order->Enrollment))
			return '</div>';

		$return = '<div align="left" style="border: 1px solid #BBBBBB; background-color: #efefef;
padding: 24px; margin: 24px; font-size: 11px; color: #555;
">';
		if (A25_DrivingPermitDiscount::studentGetsDiscount($this->_order->Enrollment)) {
			$return .= '<p><i><b>*</b> You are receiving a discounted tuition because
			you are taking the course in order to obtain your driving permit.</i></p>';
		}

		// SURCHARGE_LOGIC (mark all surchage logic with this tag so that we can
		// see opportunities to separate it out)
		if ($this->shouldDisplaySurcharge()) {
				$return .= '<p>** About the <i>DOR Fee</i> &mdash; Colorado Revised Statute 42-4-1717, requires defendants who have
violated traffic laws and who agree to or are ordered by a court to attend a
driver improvement school/course, to pay a $'
			. $this->_order->courtSurchargeAmount()
			. ' penalty surcharge.  This
surcharge is collected by the driver improvement school and is remitted in full
to the Colorado Department of Revenue.  The funds generated through the
collection of the penalty surcharge are used by the Colorado Department of
Revenue to underwrite the administrative costs associated with a driver
improvement school quality control program established by this statute.  Driver
improvement schools do not retain any part of the surcharge.
</p>';
		}
		$return .= '
</div>
</div>';
		return $return;
	}
}
