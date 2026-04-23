<?php

class A25_OldCom_Admin_PaymentView
{
	/**
	 * View information for an individual payment
	 * 
	 * @param integer $pay_id
	 * @param string $option
	 * @return void
	 */
	public static function viewPay( $pay_id, $option='com_pay' ) {
		$payment = A25_Record_Pay::retrieve( $pay_id );

		A25_OldCom_Admin_PaymentViewHtml::viewPay( $payment, $option );
	}
}
?>
