<?php
/**
 * The way this class is structured is superior to the way the other Template
 * classes are structured, because it is much easier to be tested at a micro
 * level. - Thomas, 01/26/10
 */
class A25_Default_ListPayOpts extends A25_StrictObject
{
	protected $_balance;
	protected $_order;
  protected $_course;

	function __construct ($balance, A25_Record_Order $order, A25_Record_Course $course)
	{
		$this->_balance = $balance;
		$this->_order = $order;
    $this->_course = $course;
	}
	private function previousBalance()
	{
		$return = ($this->_balance - $this->_order->tuitionFee()
				- $this->_order->lateFee() - $this->_order->courtSurchargeAmount()
                - $this->_order->replaceCertFee() - $this->_order->returnCheckFee()
                - $this->_order->noShowFee() - $this->_order->creditCardFee()
                - $this->_order->virtualCourseFee());
    if ($this->_order->hasFeeOfType(A25_Record_OrderItemType::typeId_Donation))
      $return = $return - $this->_order->donationFee();
    return $return;
	}
	private function order()
	{
		return $this->_order;
	}
	public function shouldDisplaySurcharge()
	{
		return ($this->_order->courtSurchargeAmount() > 0);
	}
  protected function shouldDisplayLateFee()
  {
    return ($this->_order->getLateFeeLineItem());
  }
	public function footer()
	{
		if( !$this->shouldDisplaySurcharge() && !$this->shouldDisplayLateFee())
			return '</div>';
		$return = '<div style="border: 1px solid #BBBBBB; background-color: #efefef;
padding: 1em; margin: 1em;
">';
    if ($this->shouldDisplayLateFee()) {
      $return .= '<p>' . $this->_course->lateFeeFootnote() . '</p>';
    }

		// SURCHARGE_LOGIC (mark all surchage logic with this tag so that we can
		// see opportunities to separate it out)
		if ($this->shouldDisplaySurcharge()) {
      $return .= '<p>** About the <i>DOR Fee</i> &mdash; '
        . PlatformConfig::surchargeFootnote($this->_order->courtSurchargeAmount())
        . '</p>';
		}
		$return .= '
</div>
</div>';
		return $return;
	}
	public function orderSummary()
	{
		$return = '<table id="orderSummary" cellspacing="0" cellpadding="2"
			style="font-size: larger; width: 100%">
<td colspan="2" style="border-bottom: 1px solid black;
font-weight: bold;
">Order Summary</td>
<tr><td>' . $this->tuitionLabel() . '</td><td align="right">$'
		. $this->order()->tuitionFee() . '</td></tr>';

		if ($this->order()->hasLateFee()) {
			$return .= '<tr><td>';
            if ($this->_course->course_type_id == A25_Record_Course::typeId_Spanish) {
                $return .= 'Cargo por pago tard&iacute;o';
            }
            else {
                $return .= 'Late Fee';
            }
            $return .= '*</td><td align="right">$'
            . $this->order()->lateFee() . '</td></tr>';
		}

		// SURCHARGE_LOGIC (mark all surchage logic with this tag so that we can
		// see opportunities to separate it out)
		if ($this->shouldDisplaySurcharge()) {
			$return .= '<tr><td>DOR Fee**';
            if (A25_DI::PlatformConfig()->allowCourtSurchargeWaive) {
                $surcharge = $this->order()->getSurchargeLineItem();
                $return .= ' &nbsp;<span style="font-size: smaller; '
        . 'font-style: italic;">(If you received a waiver form from '
        . 'the court, <a href="waive-surcharge?item_id=' . $surcharge->item_id
        . '">click here</a>.)</span>';
            }
			$return .= '</td><td align="right">$'
				. $this->order()->courtSurchargeAmount() . '</td></tr>';
		}
    
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_ReplaceCertFee)) {
      $return .= '<tr><td>Replacement Certificate Fee</td><td align="right">$'
					. $this->order()->replaceCertFee() . '</td></tr>';
    }
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_ReturnCheckFee)) {
      $return .= '<tr><td>Returned Check Fee</td><td align="right">$'
					. $this->order()->returnCheckFee() . '</td></tr>';
    }
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows)) {
      $return .= '<tr><td>No Show Fee</td><td align="right">$'
					. $this->order()->noShowFee() . '</td></tr>';
    }
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_CreditCardFee)) {
      $return .= '<tr><td>Credit Card Processing Fee</td><td align="right">$'
					. $this->order()->creditCardFee() . '</td></tr>';
    }
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_Donation)) {
      $return .= '<tr><td>Donation</td><td align="right">$'
					. $this->order()->donationFee() . '</td></tr>';
    }
    if ($this->order()->hasFeeOfType(
        A25_Record_OrderItemType::typeId_VirtualCourseFee)) {
      $return .= '<tr><td>Virtual Course Fee</td><td align="right">$'
					. $this->order()->virtualCourseFee() . '</td></tr>';
    }

		if ($this->previousBalance() > 0) {
			$return .= '<tr><td>Unpaid amount owed from previous classes</td><td align="right">$'
					. $this->previousBalance() . '</td></tr>';
		} else if ($this->previousBalance() < 0) {
			$return .= '<tr><td>Credits from previous payments</td><td align="right">(-$'
					. (-$this->previousBalance()) . ')</td></tr>';
		}
		
		$return .= '
<tr style="color: #BB2222; font-weight: bold;"><td style="border-top: 
1px solid black;">';
            if ($this->_course->course_type_id == A25_Record_Course::typeId_Spanish) {
                $return .= 'Pago pendiente';
            }
            else {
                $return .= 'Payment Due';
            }
        $return .= '</td><td style="border-top: 1px solid black;" align="right">$<span id="paymentDue">'
		. $this->_balance . '</span></td></tr>
</table>';
		return $return;
	}
  
	protected function tuitionLabel()
	{
        if ($this->_course->course_type_id == A25_Record_Course::typeId_Spanish) {
            return 'Matr&iacute;cula';
        }
		return 'Tuition';
	}
}
