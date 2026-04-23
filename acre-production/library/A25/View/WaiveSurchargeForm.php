<?php
class A25_View_WaiveSurchargeForm extends A25_StrictObject
{
	public function __construct()
	{
	}
	public function run()
	{
		$return = '<div class="shell">
<div class="colHeader">Notification of Court Waiver</div>
<div id="colContent">';
		$return .= '<p>State law requires us to collect a $25
			surcharge for the Department of Revenue, unless the court
			waived the surcharge.  If the court determined
			that you are unable to pay the
			DOR surcharge, they filled out <i>Form DR 2466: Driver Improvement
			School Referral Form</i>.  They either gave you the form to send in yourself, or they
			told you that they will send it in.  We cannot give
			you a certificate of completion until we receive the original form.
			Please select the way
			that the court told you to proceed:';
		$return .= '<form method="GET" action="execute-waive-surcharge" tmt:validate="true">';
		$return .= '<input type="hidden" name="item_id" value="' . $_REQUEST['item_id'] . '" />';
    $return .= '<p><input type="radio" name="waive" value="'
			. A25_Record_OrderItem::waiveType_Student_SelfSend
			. '" checked /> The court
			waived the surcharge and gave me the signed waiver form.
			I will send the <b>original copy</b> to the Alive at 25 office.<br/>';
		$return .= '<input type="radio" name="waive" value="'
			. A25_Record_OrderItem::waiveType_Student_CourtSend
			. '" /> The court waived the
			surcharge and submitted the
			form directly to Alive at 25.</p>';
		$return .= '<p><input type="checkbox"tmt:minchecked="1" tmt:errorclass="invalid"
			tmt:message="Please select the checkbox acknowledging your understanding."/>
			By checking this box, I assert that
			the above information is true.  I understand that I will not receive
			my certificate of completion until Alive at 25 receives the
			<b>original copy</b> of the waiver form.  I also understand that
			if the court did not actually waive my fee and I pay the surcharge late, I
			will be charged a $10 late fee.</p>';
		$return .= '<input type="submit" value="Submit" /> &nbsp;
			<input type="button" value="Cancel" onClick="history.go(-1)" />';
		$return .= '</form>
</div>
</div>';
		return $return;
	}
}
?>
