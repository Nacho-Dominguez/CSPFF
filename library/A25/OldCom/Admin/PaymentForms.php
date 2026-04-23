<?php

class A25_OldCom_Admin_PaymentForms {
	/**
	 * Show the form to submit a payment for a student.
	 * @author Christiaan van Woudenberg
	 * @version September 17, 2006
	 *
	 * @param integer $xref_id
	 * @return void
	 * @static
	 *
	 * A very similar function is found in pay.php.  There pry is some
	 * duplication.
	 */
	public static function adminPayForm(A25_Record_Enroll $enroll)
	{
		$order = $enroll->Order;

		$lists = array();

		$lists['state'] = self::generateStateSelectList($enroll->Student->state);
		$lists['expMonth'] = self::generateMonthSelectList();
		$lists['expYear'] = self::generateYearSelectList();
		$lists['credit_type_id'] = self::generateCreditTypeSelectList();

		$account_balance = $enroll->Student->getAccountBalance();

		A25_OldCom_Admin_PaymentFormsHtml::payForm($order, $lists, $account_balance);
	}
	
	private static function generateStateSelectList($state)
	{
		return A25_SelectListGenerator::generateStateSelectList('x_state',
			' class="inputbox" tmt:invalidindex="0" tmt:message="Please select your billing address state."',
			$state);
	}
	private static function generateMonthSelectList()
	{
		return mosHTML::monthSelectList( 'expMonth','id="expMonth" tmt:invalidindex="0" tmt:message="Please select an expiration month."','');
    }
	/**
     * Before refactoring, search through code for duplication, and make comments
     * to use this function.  It will be harder to spot the duplication once we
     * refactor.
     * 
     * @return <type> 
     */
	private static function generateYearSelectList()
	{
		$years = array();
		$years[] = mosHTML::makeOption('','- Select One -');
		for ($i=date("Y");$i<date("Y")+11;$i++) {
			$years[] = mosHTML::makeOption($i,$i);
		}
		return mosHTML::selectList( $years, 'expYear', 'id="expYear" class="inputbox" tmt:invalidindex="0" tmt:message="Please select an expiration year."', 'value', 'text', null);
    }
	/**
     * Before refactoring, search through code for duplication, and make comments
     * to use this function.  It will be harder to spot the duplication once we
     * refactor.
     * 
     * @return <type> 
     */
	private static function generateCreditTypeSelectList()
	{
				//get list of credit types
		$credit_type_id = trim( mosGetParam( $_REQUEST, 'credit_type_id', null ) );
		$credit_type = new A25_Record_CreditType();
		$credit_types = array();
		$credit_types[] = mosHTML::makeOption('','- Select A Credit/Scholarship Type -');
		$credit_types = array_merge($credit_types,$credit_type->getCreditTypeListACL());
		return mosHTML::selectList( $credit_types, 'credit_type_id', 'id="credit_type_id" tmt:invalidindex="0" tmt:message="Please select a credit/scholarship type."','credit_type_id', 'credit_full_name', $credit_type_id);
    }
}
